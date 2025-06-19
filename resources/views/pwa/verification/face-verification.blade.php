<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinniAttend - Face Id Verification</title>
    @vite('resources/css/app.css')
    <script src="/js/face-api.min.js"></script>
</head>
<body class="bg-white font-poppins">
    <div class="max-w-full mx-auto bg-white min-h-screen shadow-sm relative flex flex-col items-center justify-center">
        <header class="px-6 py-4 w-full flex items-center justify-between">
            <a href="{{url('/attendance/check-in')}}" class="text-gray-700">
                <i class="fa fa-chevron-left"></i>
            </a>
            <h1 class="text-sm font-semibold text-gray-800 mx-auto">Face Verification</h1>
            <span></span>
        </header>

        <div class="flex flex-col items-center mt-8">
            <video id="video" width="320" height="320" autoplay muted class="rounded-lg border shadow"></video>
            <div id="nameDiv" class="text-center text-lg font-bold mt-4"></div>
            <div id="statusDiv" class="text-center text-green-600 font-semibold mt-2"></div>
        </div>
    </div>

    <script>
        const users = @json($users);

        const labelImages = {};
        users.forEach(u => {
            if (u.profile_photo) {
                labelImages[u.name] = [`/storage/${u.profile_photo}`];
            }
        });
        const labels = Object.keys(labelImages);

        const video = document.getElementById("video");
        let absenSent = false;
        let userLocation = { latitude: null, longitude: null };

        // Ambil lokasi user
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLocation.latitude = position.coords.latitude;
                    userLocation.longitude = position.coords.longitude;
                },
                (error) => {
                    console.warn('Lokasi tidak diizinkan:', error);
                }
            );
        }

        Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri("/assets/models"),
            faceapi.nets.faceRecognitionNet.loadFromUri("/assets/models"),
            faceapi.nets.faceLandmark68Net.loadFromUri("/assets/models"),
        ]).then(startWebcam);

        function startWebcam() {
            navigator.mediaDevices
                .getUserMedia({ video: true, audio: false })
                .then((stream) => { video.srcObject = stream; })
                .catch((error) => { console.error(error); });
        }

        async function getLabeledFaceDescriptions() {
            return Promise.all(
                labels.map(async (label) => {
                    const descriptions = [];
                    for (let i = 0; i < labelImages[label].length; i++) {
                        const img = await faceapi.fetchImage(labelImages[label][i]);
                        const detections = await faceapi
                            .detectSingleFace(img)
                            .withFaceLandmarks()
                            .withFaceDescriptor();
                        if (detections) {
                            descriptions.push(detections.descriptor);
                        }
                    }
                    return new faceapi.LabeledFaceDescriptors(label, descriptions);
                })
            );
        }

        video.addEventListener("play", async () => {
            const labeledFaceDescriptors = await getLabeledFaceDescriptions();
            if (!labeledFaceDescriptors.length) {
                alert('Tidak ada data wajah yang bisa diproses!');
                return;
            }
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

            const canvas = faceapi.createCanvasFromMedia(video);
            canvas.style.position = "absolute";
            canvas.style.left = video.offsetLeft + "px";
            canvas.style.top = video.offsetTop + "px";
            document.body.append(canvas);

            const displaySize = { width: video.width, height: video.height };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                const detections = await faceapi
                    .detectAllFaces(video)
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(
                    detections,
                    displaySize
                );

                canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

                const results = resizedDetections.map((d) => {
                    return faceMatcher.findBestMatch(d.descriptor);
                });
                results.forEach((result, i) => {
                    document.getElementById("nameDiv").innerText = result._label;
                    if(result._label !== "unknown" && !absenSent) {
                        absenSent = true;
                        document.getElementById("statusDiv").innerText = "Mencatat kehadiran...";
                        fetch('/attendance/face-verification', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                name: result._label,
                                latitude: userLocation.latitude,
                                longitude: userLocation.longitude
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById("statusDiv").innerText = "Absen berhasil untuk " + result._label;
                        })
                        .catch(() => {
                            document.getElementById("statusDiv").innerText = "Gagal absen!";
                        });
                    }
                });
            }, 1000);
        });
    </script>
</body>
</html>