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
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm relative justify-center">
        <!-- Header -->
        <header class="px-6 py-4 relative flex items-center">
            <a href="{{url('/attendance/check-in')}}" class="absolute left-6">
                <i class="fa fa-chevron-left text-gray-700"></i>
            </a>
            <h1 class="mx-auto text-sm font-semibold text-gray-800">Face Verification</h1>
        </header>

        <!-- Instructions -->
        <div class="px-6 text-center mt-12">
            <p class="text-base text-center font-normal">Follow the instructions to complete</p>
            <p class="text-base text-center font-normal">face verification!</p>
        </div>

        <!-- Face ID Scanner with SVG -->
        <div class="flex justify-center items-center mt-8 mb-12 relative">
            <div class="relative w-[177px] h-[176px] items-center justify-center">
                <!-- SVG Circle -->
                <svg width="177" height="176" viewBox="0 0 177 176" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute top-0 left-0 z-10">
                    <circle cx="88.5" cy="88" r="85.5" stroke="#FF66C4" stroke-width="5"/>
                </svg>
                
                <!-- Video Camera Feed -->
                <video id="cameraFeed" autoplay playsinline class="absolute top-0 left-0 w-full h-full object-cover rounded-full z-0 scale-x-[-1]">
                </video>
            </div>
        </div>

        
        <!-- Next Button -->
        <div class="fixed bottom-92 left-0 right-0 flex justify-center text-center">
            <a href="#" class="text-center text-blue-500 font-semibold text-sm">
                Blink your eyes
            </a>
        </div>
    </div>

    <!-- JavaScript for camera activation -->
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
                        height: { ideal: 300 },
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
    </script>
</body> 
</html>