<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WinniAttend - Face Id Verification</title>

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
</head>
<body class="bg-white font-poppins">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm relative">
        <!-- Header -->
        <header class="px-6 py-4 relative flex items-center">
            <a href="{{url('/attendance/check-in')}}" class="absolute left-6">
                <i class="fa fa-chevron-left text-gray-700"></i>
            </a>
            <h1 class="mx-auto text-sm font-semibold text-gray-800">Face Verification</h1>
        </header>

        <!-- Face ID Scanner with SVG -->
        <div class="flex justify-center items-center mt-24 mb-12 relative">
            <div class="relative w-50 h-50">
                {{-- <video id="cameraFeed" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover rounded-lg"></video>
                 --}}
                <!-- SVG Overlay -->
                <svg width="175" height="175" viewBox="0 0 175 175" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 w-full h-full">
                    <path d="M5.83337 58.3333V29.1666C5.83337 22.9783 8.2917 17.0433 12.6675 12.6675C17.0434 8.29164 22.9783 5.83331 29.1667 5.83331H58.3334M116.667 5.83331H145.833C152.022 5.83331 157.957 8.29164 162.333 12.6675C166.708 17.0433 169.167 22.9783 169.167 29.1666V58.3333M5.83337 116.667V145.833C5.83337 152.022 8.2917 157.957 12.6675 162.332C17.0434 166.708 22.9783 169.167 29.1667 169.167H58.3334M169.167 116.667V145.833C169.167 152.022 166.708 157.957 162.333 162.332C157.957 166.708 152.022 169.167 145.833 169.167H116.667M23.3334 87.5H151.667" stroke="url(#paint0_linear_195_51)" stroke-width="8"/>
                    <defs>
                        <linearGradient id="paint0_linear_195_51" x1="87.5" y1="5.83331" x2="87.5" y2="169.167" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#5271FF"/>
                            <stop offset="1" stop-color="#FF66C4"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="px-6 text-center">
            <p class="text-base text-center font-normal">Please Verify your Face ID first</p>
            <p class="text-base text-center font-normal">Follow the instructions to complete</p>
            <p class="text-base text-center font-normal">face verification!</p>
        </div>
        
        <!-- Next Button -->
        <div class="fixed bottom-16 left-0 right-0 flex justify-center">
            <a href="{{url('/face-register')}}" class="long-button text-center">
                Next
            </a>
        </div>
    </div>

    {{-- <!-- JavaScript for camera activation -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('cameraFeed');
            
            // Check if browser supports getUserMedia
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                // Request front camera
                navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 300 },
                        height: { ideal: 300 }
                    } 
                })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(error => {
                    console.error('Camera error:', error);
                });
            } else {
                console.error('getUserMedia is not supported in this browser');
            }
            
            // Handle page unload to stop camera
            window.addEventListener('beforeunload', () => {
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }
            });
        });
    </script> --}}
</body>
</html>