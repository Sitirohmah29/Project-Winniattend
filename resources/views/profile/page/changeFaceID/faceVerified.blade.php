<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Face Verification</title>

    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container justify-center">
    {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
        onclick="window.location.href='{{url('/changeFace')}}'"></i>
        <h2 class="text-title text-center w-full">Face Verification</h2>
    </div>

    <form id="faceForm" action="{{ route('Update.faceid') }}" method="POST" enctype="multipart/form-data">
    @csrf
        {{-- Hidden canvas untuk menangkap gambar dari video --}}
        <canvas id="canvas" style="display:none;"></canvas>

        {{-- welcome face verification --}}
        <div class="px-8  flex flex-col gap-6 text-center justify-center items-center">
            <p class="text-base text-gray-600 font-semibold ">Follow the instructions to complete face verification!</p>
            <div class="relative w-58 h-58 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full pointer-events-none z-10"
                    style="background: linear-gradient(135deg, #6a7cff 0%, #ff6adf 100%); padding: 0.35rem;">
                </div>
                <video id="video" autoplay muted playsinline
                    class="relative w-57 h-57 rounded-full object-cover z-20 border-8 border-transparent -scale-x-100"
                    style="box-shadow: 0 4px 24px 0 rgba(106,124,255,0.10);">
                </video>
            </div>
            <div class="flex flex-col gap-1">
                <p class="text-lg text-red-600">Warning!</p>
                <p class="px-8 text-base font-semibold text-gray-600">Changes cannot be reversed within one month</p>
            </div>
        </div>

        {{-- Hidden input untuk gambar base64 --}}
        <input type="hidden" name="face_image" id="faceImage">

        @if(session('success'))
            <div class="flex-con-rounded md:w-md mt-6">
                <i class="fa-solid fa-circle-check fa-2x" style="color: #63E6BE;"></i>
                <p class="text-title">{{ session('success') }}</p>
            </div>
        @endif

        {{-- button done --}}
        <div class="fixed-bottom-button">
            <button type="button" id="captureBtn" class="long-button bg-blue-600 text-white">
                Capture Face
            </button>
        </div>
    </form>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const faceImage = document.getElementById('faceImage');
        const form = document.getElementById('faceForm');

        // Akses kamera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function (err) {
                    console.error("Gagal mengakses kamera:", err);
                    alert("Gagal mengakses kamera. Pastikan sudah memberikan izin.");
                });
        } else {
            alert("Browser Anda tidak mendukung akses kamera.");
        }

        // Saat klik Capture, ambil wajah lalu submit form otomatis
        captureBtn.addEventListener('click', function () {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataURL = canvas.toDataURL('image/png');
            faceImage.value = dataURL;

            alert('Wajah berhasil diambil. Mengirim data...');
            form.submit(); // Submit otomatis ke route POST /faceVerified
        });
    });
</script>
</html>
