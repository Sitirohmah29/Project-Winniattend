<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WinniAttend - Face Id Verification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WinniAttend">
    <link rel="apple-touch-icon" href="images/icons/icon-192x192.png">
    <link rel="manifest" href="manifest.json">
    
    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .capture-button {
            transition: all 0.3s ease;
        }
        .capture-button:hover {
            transform: scale(1.05);
        }
        .capture-button:active {
            transform: scale(0.95);
        }
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #FF66C4;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .success-message {
            background: linear-gradient(135deg, #10B981, #059669);
        }
        .error-message {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }
    </style>
</head>
<body class="bg-white font-poppins">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm relative justify-center">
        <!-- Header -->
        <header class="px-6 py-4 relative flex items-center">
            <a href="{{url('/attendance/check-in')}}" class="absolute left-6">
                <i class="fa fa-chevron-left text-gray-700"></i>
            </a>
            <h1 class="mx-auto text-sm font-semibold text-gray-800">Face Register</h1>
        </header>

        <!-- Instructions -->
        <div class="px-6 text-center mt-8">
            <p class="text-base text-center font-normal text-gray-600">Position your face within the circle</p>
            <p class="text-base text-center font-normal text-gray-600">and tap capture when ready!</p>
        </div>

        <!-- Face ID Scanner with SVG -->
        <div class="flex justify-center items-center mt-8 mb-8 relative">
            <div class="relative w-[250px] h-[250px] items-center justify-center">
                <!-- SVG Circle -->
                {{-- <svg width="250" height="250" viewBox="0 0 250 250" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 z-10">
                    <circle cx="125" cy="125" r="120" stroke="#FF66C4" stroke-width="4" stroke-dasharray="10,5" class="animate-pulse"/>
                </svg> --}}
                <svg width="250" height="250" viewBox="0 0 177 176" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 z-10">
                    <circle cx="88.5" cy="88" r="85.5" stroke="#FF66C4" stroke-width="5"/>
                </svg>
                
                <!-- Video Camera Feed -->
                <video id="cameraFeed" autoplay playsinline muted class="absolute top-0 left-0 w-full h-full object-cover rounded-full z-0 scale-x-[-1]">
                </video>
                
                <!-- Canvas for capturing -->
                <canvas id="captureCanvas" class="hidden"></canvas>
            </div>
        </div>

        <!-- Status Messages -->
        <div id="statusMessage" class="hidden mx-6 p-4 rounded-lg text-white text-center mb-4">
            <span id="statusText"></span>
        </div>

        <!-- Capture Button -->
        <div class="flex justify-center mb-8">
            <button id="captureBtn" class="capture-button bg-gradient-to-r from-pink-500 to-purple-600 text-white px-8 py-3 rounded-full font-semibold shadow-lg flex items-center space-x-2">
                <i class="fas fa-camera"></i>
                <span>Capture Photo</span>
            </button>
        </div>


        <!-- Verification Type Selection (Hidden input, set via URL parameter or default) -->
        <input type="hidden" id="verificationType" value="check_in">
    </div>

    <!-- JavaScript for camera activation and capture -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('cameraFeed');
            const canvas = document.getElementById('captureCanvas');
            const captureBtn = document.getElementById('captureBtn');
            const statusMessage = document.getElementById('statusMessage');
            const statusText = document.getElementById('statusText');
            const verificationType = document.getElementById('verificationType');
            
            let stream = null;
            let isCapturing = false;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get verification type from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const typeParam = urlParams.get('type');
            if (typeParam && ['check_in', 'check_out'].includes(typeParam)) {
                verificationType.value = typeParam;
            }

            // Initialize camera
            initCamera();

            function initCamera() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            facingMode: 'user',
                            width: { ideal: 640 },
                            height: { ideal: 640 },
                        } 
                    })
                    .then(mediaStream => {
                        stream = mediaStream;
                        video.srcObject = stream;
                        showStatus('Camera ready! Position your face and capture.', 'success');
                    })
                    .catch(error => {
                        console.error('Camera error:', error);
                        showStatus('Camera access denied. Please allow camera permissions.', 'error');
                    });
                } else {
                    showStatus('Camera not supported in this browser.', 'error');
                }
            }

            function showStatus(message, type) {
                statusText.textContent = message;
                statusMessage.className = `mx-6 p-4 rounded-lg text-white text-center mb-4 ${type === 'success' ? 'success-message' : 'error-message'}`;
                statusMessage.classList.remove('hidden');
                
                if (type === 'success') {
                    setTimeout(() => {
                        statusMessage.classList.add('hidden');
                    }, 3000);
                }
            }

            function showLoading(show) {
                const btnIcon = captureBtn.querySelector('i');
                const btnText = captureBtn.querySelector('span');
                
                if (show) {
                    captureBtn.disabled = true;
                    captureBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    btnIcon.className = 'loading-spinner';
                    btnText.textContent = 'Processing...';
                } else {
                    captureBtn.disabled = false;
                    captureBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    btnIcon.className = 'fas fa-camera';
                    btnText.textContent = 'Capture Photo';
                }
            }

            captureBtn.addEventListener('click', async () => {
                if (isCapturing || !stream) return;
                
                isCapturing = true;
                showLoading(true);

                try {
                    // Set canvas dimensions to match video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    
                    // Draw video frame to canvas
                    const ctx = canvas.getContext('2d');
                    ctx.scale(-1, 1); // Flip horizontal to match mirrored video
                    ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
                    
                    // Convert canvas to base64
                    const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
                    
                    // Send to server
                    const response = await fetch('/face-verification/capture', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            image: imageDataUrl,
                            verification_type: verificationType.value
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        showStatus('Face verification captured successfully!', 'success');
                        
                        // Optional: Redirect after successful capture
                        setTimeout(() => {
                            window.location.href = '/attendance/face-verification';
                        }, 2000);
                    } else {
                        showStatus(result.message || 'Failed to capture verification.', 'error');
                    }

                } catch (error) {
                    console.error('Capture error:', error);
                    showStatus('Failed to process image. Please try again.', 'error');
                } finally {
                    isCapturing = false;
                    showLoading(false);
                }
            });

            // Cleanup camera on page unload
            window.addEventListener('beforeunload', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });

            // Handle visibility change to pause/resume camera
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                } else {
                    initCamera();
                }
            });
        });
    </script>
</body> 
</html>