// resources/js/face-verification.js
import * as faceapi from "face-api.js";
import * as faceLandmarksDetection from "@tensorflow-models/face-landmarks-detection";

let video,
    model,
    isDetecting = false;
let blinkCount = 0;
const requiredBlinks = 3;
let eyeOpenHistory = [];
let faceDetectionStable = false;
let stableFrameCount = 0;
let verificationMode = "register";

const headMovementState = {
    isDetecting: false,
    currentXPosition: 0,
    startXPosition: 0,
    movementThreshold: 50,
    detectedMovements: [],
};

const mouthOpenState = {
    isDetecting: false,
    isOpen: false,
    mouthAspectRatioHistory: [],
    threshold: 0.45,
};

export async function initFaceVerification(config) {
    const {
        videoId,
        statusTextId,
        instructionTextId,
        ringId,
        blinkProgressId,
        messageBoxId,
        retryButtonId,
        continueButtonId,
        redirectUrl,
        registerUrl,
        verifyUrl,
        statusUrl,
        csrfToken,
    } = config;

    // DOM references
    video = document.getElementById(videoId);
    const statusText = document.getElementById(statusTextId);
    const instructionText = document.getElementById(instructionTextId);
    const ring = document.getElementById(ringId);
    const blinkProgress = document.getElementById(blinkProgressId);
    const messageBox = document.getElementById(messageBoxId);
    const retryButton = document.getElementById(retryButtonId);
    const continueButton = document.getElementById(continueButtonId);

    retryButton.addEventListener("click", () => window.location.reload());
    continueButton.addEventListener(
        "click",
        () => (window.location.href = redirectUrl)
    );

    await checkStatus();
    await initCamera();
    await loadModels();
    startDetection();

    async function checkStatus() {
        const res = await fetch(statusUrl);
        const json = await res.json();
        verificationMode = json.data?.has_registration ? "verify" : "register";
        statusText.textContent =
            verificationMode === "register"
                ? "No face registered. Registering..."
                : "Face detected. Verifying...";
    }

    async function initCamera() {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "user" },
        });
        video.srcObject = stream;
        await new Promise((resolve) => (video.onloadedmetadata = resolve));
        video.play();
    }

    async function loadModels() {
        model = await faceLandmarksDetection.load(
            faceLandmarksDetection.SupportedPackages.mediapipeFacemesh,
            { maxFaces: 1 }
        );
        await faceapi.nets.ssdMobilenetv1.loadFromUri("/models");
        await faceapi.nets.faceLandmark68Net.loadFromUri("/models");
        await faceapi.nets.faceRecognitionNet.loadFromUri("/models");
    }

    function startDetection() {
        isDetecting = true;
        requestAnimationFrame(detectLoop);
    }

    async function detectLoop() {
        if (!isDetecting || video.readyState !== 4) {
            requestAnimationFrame(detectLoop);
            return;
        }
        const predictions = await model.estimateFaces({ input: video });
        if (predictions.length > 0) {
            const prediction = predictions[0];
            handlePrediction(prediction);
        }
        requestAnimationFrame(detectLoop);
    }

    async function handlePrediction(prediction) {
        if (!faceDetectionStable) {
            stableFrameCount++;
            if (stableFrameCount >= 10) {
                faceDetectionStable = true;
            }
            return;
        }
        if (blinkCount < requiredBlinks) {
            const left = eyeRatio(
                prediction.annotations.leftEyeUpper0,
                prediction.annotations.leftEyeLower0
            );
            const right = eyeRatio(
                prediction.annotations.rightEyeUpper0,
                prediction.annotations.rightEyeLower0
            );
            detectBlink((left + right) / 2 < 0.15);
        } else if (headMovementState.detectedMovements.length < 2) {
            detectHeadMove(prediction);
        } else if (!mouthOpenState.isOpen) {
            detectMouth(prediction);
        } else {
            const canvas = captureCanvas();
            const descriptor = await getDescriptor(canvas);
            if (!descriptor) return;

            const response =
                verificationMode === "register"
                    ? await sendData(registerUrl, descriptor, canvas)
                    : await sendData(verifyUrl, descriptor);

            if (response.success) {
                statusText.textContent = "Success";
                messageBox.textContent = response.message;
                continueButton.style.display = "block";
            } else {
                statusText.textContent = "Failed";
                messageBox.textContent = response.message;
                retryButton.style.display = "block";
            }
            isDetecting = false;
        }
    }

    function eyeRatio(upper, lower) {
        if (!upper.length || !lower.length) return 0.2;
        const vertical = Math.abs(upper[0][1] - lower[0][1]);
        const horizontal = Math.abs(upper[0][0] - upper[upper.length - 1][0]);
        return vertical / horizontal;
    }

    function detectBlink(closed) {
        eyeOpenHistory.push(closed);
        if (eyeOpenHistory.length > 10) eyeOpenHistory.shift();
        const last3 = eyeOpenHistory.slice(-3);
        if (!last3[0] && last3[1] && !last3[2]) blinkCount++;
    }

    function detectHeadMove(pred) {
        const noseTip = pred.annotations.noseLower[2][0];
        const diff = noseTip - headMovementState.startXPosition;
        if (!headMovementState.startXPosition)
            headMovementState.startXPosition = noseTip;
        if (Math.abs(diff) > headMovementState.movementThreshold) {
            const dir = diff > 0 ? "right" : "left";
            if (!headMovementState.detectedMovements.includes(dir)) {
                headMovementState.detectedMovements.push(dir);
            }
        }
    }

    function detectMouth(pred) {
        const up = pred.annotations.lipsUpper;
        const low = pred.annotations.lipsLower;
        const height = Math.abs(up[5][1] - low[5][1]);
        const width = Math.abs(up[0][0] - up[up.length - 1][0]);
        const ratio = height / width;
        if (ratio > mouthOpenState.threshold) mouthOpenState.isOpen = true;
    }

    function captureCanvas() {
        const c = document.createElement("canvas");
        c.width = video.videoWidth;
        c.height = video.videoHeight;
        const ctx = c.getContext("2d");
        ctx.scale(-1, 1);
        ctx.drawImage(video, -c.width, 0, c.width, c.height);
        return c;
    }

    async function getDescriptor(canvas) {
        const detection = await faceapi
            .detectSingleFace(canvas)
            .withFaceLandmarks()
            .withFaceDescriptor();
        return detection?.descriptor || null;
    }

    async function sendData(url, embedding, canvas = null) {
        const payload = {
            face_embedding: Array.from(embedding),
            threshold: 0.6,
        };
        if (canvas) {
            payload.face_image = canvas.toDataURL("image/jpeg");
            payload.registration_date = new Date().toISOString();
            payload.device_info = {
                user_agent: navigator.userAgent,
                screen_resolution: `${screen.width}x${screen.height}`,
                video_resolution: `${video.videoWidth}x${video.videoHeight}`,
                timestamp: new Date().toISOString(),
            };
        }
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(payload),
        });
        return await res.json();
    }
}

// Usage of initFaceVerification should be added to your HTML entrypoint with correct config keys
