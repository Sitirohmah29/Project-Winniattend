<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinniAttend - Face ID Verification</title>
    {{-- Include application CSS compiled by Vite --}}
    @vite('resources/css/app.css')
    {{-- Face-API.js library for face detection and recognition --}}
    <script src="/js/face-api.min.js"></script>
    {{-- Alpine.js for declarative JavaScript --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    {{-- Font Awesome for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-[#f5f9fc] font-poppins min-h-screen flex flex-col">
    <header class="px-6 py-4 relative flex items-center">
        {{-- Back button to navigate to the attendance check-in page --}}
        <a href="{{ url('/attendance/check-in') }}" class="absolute left-6">
            <i class="fa fa-chevron-left text-gray-700 cursor-pointer hover:text-blue-500"></i>
        </a>
        {{-- Page title --}}
        <h1 class="mx-auto text-sm font-semibold text-gray-800">Face Verification</h1>
    </header>

    <div class="flex flex-col items-center mt-6">
        <div class="relative w-62 h-62 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full pointer-events-none z-10"
                style="background: linear-gradient(135deg, #6a7cff 0%, #ff6adf 100%); padding: 0.35rem;">
            </div>
            <video id="video" autoplay muted playsinline
                class="relative w-61 h-61 rounded-full object-cover z-20 border-8 border-transparent -scale-x-100"
                style="box-shadow: 0 4px 24px 0 rgba(106,124,255,0.10);">
            </video>
        </div>

        <div class="flex flex-col items-center mt-10">
            <div class="bg-white rounded-3xl shadow-lg px-4 py-6 w-[90%] max-w-full relative">
                <div class="flex flex-col items-center -mt-14 mb-2">
                    {{-- Status icon container --}}
                    <div class="bg-white rounded-full p-2 shadow-md mb-2">
                        <div class="bg-gradient-to-r from-blue-500 to-pink-400 rounded-full p-2">
                            {{-- SVG icon for status indication --}}
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="11" stroke="black" stroke-width="2"
                                    fill="white" />
                                <path stroke="blue" stroke-width="2.5" d="M7 13l3 3 7-7" />
                            </svg>
                        </div>
                    </div>
                    {{-- Display verification status --}}
                    <h2 class="text-xl text-center font-bold text-blue-600" id="cardStatus">Waiting for verification...
                    </h2>
                    {{-- Display detected user's name --}}
                    <p class="text-gray-500 text-sm" id="cardName">-</p>
                </div>
                {{-- Details card (Face ID, Shift, Location) --}}
                <div class="bg-white rounded-2xl shadow px-4 py-4 mt-4">
                    <div class="flex justify-between text-gray-500 text-sm font-medium">
                        <span>Face ID</span>
                        <span>Shift</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mb-2">
                        {{-- Display detected face ID --}}
                        <span class="text-black" id="cardFaceId">-</span>
                        {{-- Display shift information --}}
                        <span class="text-black ml-10" id="cardShift">-</span>
                    </div>
                    <div class="mt-2 text-gray-500 text-sm font-medium">Location</div>
                    {{-- Display user's current location --}}
                    <div class="font-bold text-black" id="cardLocation">Getting location...</div>
                    {{-- Realtime clock for check-in --}}
                    <div class="flex flex-col mt-4">
                        <p class="text-gray-500 text-sm font-medium">Check-in Time:</p>
                        <p id="realtime-clock" class="text-blue-600 text-lg font-bold text-center"></p>
                    </div>
                </div>
            </div>
            {{-- Done button to submit attendance --}}
            <button id="btnDone"
                class="mt-8 w-[60%] bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-full shadow-lg text-lg"
                disabled>
                Done
            </button>
        </div>
    </div>

    <script>
        // --- Configuration from Backend ---
        // Get authenticated user's ID (or null if not authenticated)
        const userId = {{ Auth::id() ?? 'null' }};
        // Array of face images with their names and paths from the backend
        const faceImages = @json($faceImages);
        // Organize face images by label (name) for easier access
        const labelImages = {};
        faceImages.forEach(img => {
            if (!labelImages[img.name]) labelImages[img.name] = [];
            labelImages[img.name].push(img.path);
        });
        // Extract unique labels (names) from the face images
        const labels = Object.keys(labelImages);

        // --- DOM Elements ---
        const video = document.getElementById("video");
        const btnDone = document.getElementById("btnDone");
        const cardStatus = document.getElementById("cardStatus");
        const cardName = document.getElementById("cardName");
        const cardFaceId = document.getElementById("cardFaceId");
        const cardShift = document.getElementById("cardShift");
        const cardLocation = document.getElementById("cardLocation");

        // --- State Variables ---
        let detectedUser = null; // Stores the name of the detected user
        let userLocation = {
            latitude: null,
            longitude: null
        }; // Stores user's geographical coordinates
        let isProcessing = false; // Flag to prevent multiple attendance submissions

        // Realtime clock for check-in
        function updateClock() {
            const now = new Date();
            currentTime = now;
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('realtime-clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Function to get the user's current geographical location
        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userLocation.latitude = position.coords.latitude;
                        userLocation.longitude = position.coords.longitude;

                        // Reverse geocoding: convert coordinates to a human-readable address
                        fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${userLocation.latitude}&lon=${userLocation.longitude}`
                            )
                            .then(res => res.json())
                            .then(data => {
                                cardLocation.innerText = data.display_name || "Location not found";
                                userLocationName = data.display_name || null; // Simpan nama lokasi
                            })
                            .catch(() => {
                                cardLocation.innerText = "Failed to get location";
                                userLocationName = null;
                            });
                    },
                    (error) => {
                        console.error('Geolocation error:', error);
                        cardLocation.innerText = "Location permission denied or unavailable";
                    }
                );
            } else {
                cardLocation.innerText = "Geolocation not supported by this browser";
            }
        }

        // --- Face-API.js Model Initialization ---
        // Load all required face detection models asynchronously
        Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri("/assets/models"), // For face detection
            faceapi.nets.faceRecognitionNet.loadFromUri("/assets/models"), // For face recognition
            faceapi.nets.faceLandmark68Net.loadFromUri("/assets/models"), // For detecting facial landmarks
        ]).then(() => {
            console.log('Face detection models loaded');
            startWebcam(); // Start webcam after models are loaded
            getUserLocation(); // Get user's location
        }).catch(error => {
            console.error('Failed to load face detection models:', error);
            cardStatus.innerText = "Failed to load face detection models";
        });

        // Function to start the webcam stream
        function startWebcam() {
            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: false
                }) // Request video access
                .then((stream) => {
                    video.srcObject = stream; // Set video source to the webcam stream
                })
                .catch((error) => {
                    console.error('Camera access error:', error);
                    cardStatus.innerText = "Failed to access camera";
                });
        }

        // Function to load labeled face descriptors from provided images
        async function getLabeledFaceDescriptions() {
            return Promise.all(
                labels.map(async (label) => {
                    const descriptions = [];
                    for (let i = 0; i < labelImages[label].length; i++) {
                        try {
                            const img = await faceapi.fetchImage(labelImages[label][i]);
                            // Detect a single face and compute its descriptor
                            const detections = await faceapi
                                .detectSingleFace(img)
                                .withFaceLandmarks()
                                .withFaceDescriptor();
                            if (detections) {
                                descriptions.push(detections.descriptor);
                            }
                        } catch (error) {
                            console.error(`Error processing image for ${label}:`, error);
                        }
                    }
                    // Return LabeledFaceDescriptors for the current label
                    return new faceapi.LabeledFaceDescriptors(label, descriptions);
                })
            );
        }

        // Event listener for when the video metadata is loaded (meaning the webcam is ready)
        video.addEventListener("loadedmetadata", async () => {
            const displaySize = {
                width: video.videoWidth,
                height: video.videoHeight
            };
            // Create a canvas overlay for drawing detections
            const canvas = faceapi.createCanvasFromMedia(video);
            canvas.style.position = "absolute";
            canvas.style.left = video.offsetLeft + "px";
            canvas.style.top = video.offsetTop + "px";
            document.body.append(canvas);

            faceapi.matchDimensions(canvas, displaySize);

            try {
                // Get labeled face descriptors from the pre-loaded images
                const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                if (!labeledFaceDescriptors.length) {
                    cardStatus.innerText = "No face data available!";
                    return;
                }
                // Create a FaceMatcher to compare detected faces with known faces
                const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors,
                    0.45); // 0.45 is the recognition threshold

                // Set up an interval to continuously detect and recognize faces from the video stream
                setInterval(async () => {
                    try {
                        // Detect all faces in the video frame, along with landmarks and descriptors
                        const detections = await faceapi
                            .detectAllFaces(video)
                            .withFaceLandmarks()
                            .withFaceDescriptors();

                        // Resize detections to match the display size of the canvas
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);
                        // Clear the canvas before drawing new detections
                        canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

                        if (resizedDetections.length > 0) {
                            // Match each detected face with the known face descriptors
                            const results = resizedDetections.map((d) => {
                                return faceMatcher.findBestMatch(d.descriptor);
                            });

                            let found = false;
                            results.forEach((result, i) => {
                                if (result._label !== "unknown") {
                                    // Face detected and recognized
                                    detectedUser = result._label;

                                    cardName.innerText = result._label;

                                    cardFaceId.innerText = result._label;

                                    cardShift.innerText =
                                        "{{ $shiftLabel }} ({{ $shiftTime }})";

                                    cardStatus.innerText =
                                        "Face detected! Press Done to check in.";
                                    btnDone.disabled = false; // Enable the Done button
                                    found = true;
                                }
                            });
                            if (!found) {
                                // Face detected but not recognized (label is "unknown")
                                detectedUser = null;
                                cardName.innerText = "Unknown";
                                cardFaceId.innerText = "Unknown";
                                cardShift.innerText = "-";
                                cardStatus.innerText = "Face not recognized.";
                                btnDone.disabled = true; // Disable the Done button
                            }
                        } else {
                            // No face detected in the frame
                            detectedUser = null;
                            cardName.innerText = "-";
                            cardFaceId.innerText = "-";
                            cardShift.innerText = "-";
                            cardStatus.innerText =
                                "Waiting for verification..."; // Reset status if no face
                            btnDone.disabled = true; // Disable the Done button
                        }
                    } catch (error) {
                        console.error('Face detection error:', error);
                    }
                }, 1000); // Run face detection every 1 second

            } catch (error) {
                console.error('Error initializing face matcher:', error);
                cardStatus.innerText = "Failed to initialize face detector";
            }
        });

        // --- Event Listener for Done Button ---
        btnDone.addEventListener("click", function() {
            if (!detectedUser) {
                cardStatus.innerText = "No face detected!";
                return;
            }

            if (isProcessing) {
                return; // Prevent multiple submissions if already processing
            }

            isProcessing = true; // Set processing flag
            btnDone.disabled = true; // Disable button to prevent re-clicks
            cardStatus.innerText = "Processing attendance...";

            const now = new Date();
            const pad = (n) => n.toString().padStart(2, '0');
            const check_in_time =
                `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

            const attendanceData = {
                user_id: userId,
                latitude: userLocation.latitude,
                longitude: userLocation.longitude,
                check_in_location: userLocationName,
                check_in_time: check_in_time // tambahkan ini
            };
            // Send attendance data to the backend via POST request
            fetch('{{ route('attendance.face-verification') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token for security
                    },
                    body: JSON.stringify(attendanceData) // Send data as JSON string
                })
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    if (data.success) {
                        cardStatus.innerText = data.message;
                        cardStatus.className =
                            "text-xl font-bold text-green-600"; // Set status text to green for success
                        // Redirect to check-in page after a short delay on success
                        setTimeout(() => {
                            window.location.href = '{{ route('attendance.check-in') }}';
                        }, 2000);
                    } else {
                        cardStatus.innerText = data.message || "Failed to submit attendance.";
                        cardStatus.className =
                            "text-xl font-bold text-red-600"; // Set status text to red for error
                        btnDone.disabled = false; // Re-enable button on failure
                    }
                })
                .catch(error => {
                    console.error('Attendance submission error:', error);
                    cardStatus.innerText = "Failed to contact server.";
                    cardStatus.className =
                        "text-xl font-bold text-red-600"; // Set status text to red for network error
                    btnDone.disabled = false; // Re-enable button on error
                })
                .finally(() => {
                    isProcessing = false; // Reset processing flag
                });
        });
    </script>
</body>

</html>
