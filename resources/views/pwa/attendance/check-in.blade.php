<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#ffffff" />
  <title>WinniAttend - Check In</title>

  <!-- PWA Meta Tags -->
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="WinniAttend" />
  <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}" />
  <link rel="manifest" href="{{ asset('/manifest.json') }}" />

  <!-- Tailwind CSS -->
  @vite('resources/css/app.css')

  <!-- Alpine.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body class="bg-white font-poppins">
  <div class="flex flex-col h-screen">
    <!-- Header -->
    <div class="px-4 py-3 bg-white shadow">
      <div class="flex items-center">
        <a href="{{ url('dashboard') }}" class="mr-4">
          <i class="fas fa-chevron-left text-gray-600 cursor-pointer hover:text-blue-600"></i>
        </a>
        <h1 class="text-sm font-semibold text-center flex-1">Check in</h1>
      </div>
    </div>

    <!-- Map Section (Static Info Only) -->
    <div id="map" class="w-full h-80 rounded-md relative">
      <div class="absolute bottom-5 left-2 bg-white bg-opacity-90 rounded-md shadow p-3 z-[500] max-w-xs">
        <h2 class="text-sm font-semibold mb-2">Available</h2>
        <div class="flex items-start">
          <div class="flex-shrink-0 mt-1">
            <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
              <div class="h-2 w-2 rounded-full bg-white"></div>
            </div>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-semibold">IBI Kesatuan</h3>
            <p class="text-xs font-thin">
              Jl. Ranggagading no.1 RT.02/09, Gudang, Kec.Bogor Tengah, Bogor, Indonesia 16123
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Shift Info -->
    <div class="px-4 py-3">
      <h2 class="text-sm font-semibold mb-2">Shift</h2>
      <div class="bg-white p-1">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
              <div class="h-2 w-2 rounded-full bg-white"></div>
            </div>
          </div>
          <div class="ml-3">
            <span class="text-xs font-semibold">Frontend Developer</span>
            <span class="text-xs font-thin ml-14">(07:00am - 01:00pm)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Face ID Button -->
    <div class="flex justify-center mt-15">
      <form id="punchInForm" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
        @csrf
        <input type="hidden" name="punch_in_location" value="IBI Kesatuan, Bogor" />
        <input type="hidden" name="latitude" />
        <input type="hidden" name="longitude" />
        <input type="hidden" name="shift" value="Frontend Developer" />
        <input type="hidden" name="punch_in_photo" id="punch_in_photo_input" />
        <input type="hidden" name="in_allowed_range" value="0" />

        <button type="button" id="scanFaceBtn" onclick="checkFaceID()" class="w-25 h-25 rounded-full bg-white shadow-lg flex flex-col items-center justify-center">
          <div class="w-15 h-15">
            <svg width="100%" height="100%" viewBox="0 0 57 57" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8.6377 46.287C8.6377 44.6904 9.26547 43.1592 10.3829 42.0302C11.5004 40.9012 13.016 40.267 14.5963 40.267H42.4029C43.9832 40.267 45.4988 40.9012 46.6163 42.0302C47.7337 43.1592 48.3615 44.6904 48.3615 46.287V52.307H8.6377V46.287Z" fill="#38B6FF"/>
            <path d="M39.4243 23.2103C39.4243 20.2832 38.2733 17.476 36.2247 15.4062C34.176 13.3364 31.3975 12.1736 28.5002 12.1736C25.603 12.1736 22.8244 13.3364 20.7758 15.4062C18.7271 17.476 17.5762 20.2832 17.5762 23.2103C17.5762 26.1374 18.7271 28.9446 20.7758 31.0144C22.8244 33.0842 25.603 34.247 28.5002 34.247C31.3975 34.247 34.176 33.0842 36.2247 31.0144C38.2733 28.9446 39.4243 26.1374 39.4243 23.2103Z" fill="#5271FF"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6243 8.16027C10.0975 8.16027 9.59235 8.37169 9.21986 8.74801C8.84738 9.12434 8.63812 9.63474 8.63812 10.1669V16.1869C8.63812 17.2513 8.2196 18.2721 7.47464 19.0248C6.72967 19.7774 5.71928 20.2003 4.66574 20.2003C3.6122 20.2003 2.60181 19.7774 1.85684 19.0248C1.11188 18.2721 0.693359 17.2513 0.693359 16.1869V10.1669C0.693359 7.50593 1.73965 4.95392 3.60207 3.0723C5.46448 1.19069 7.99046 0.133606 10.6243 0.133606H16.5829C17.6364 0.133606 18.6468 0.556438 19.3918 1.30908C20.1367 2.06173 20.5553 3.08254 20.5553 4.14694C20.5553 5.21134 20.1367 6.23215 19.3918 6.98479C18.6468 7.73744 17.6364 8.16027 16.5829 8.16027H10.6243ZM36.4448 4.14694C36.4448 3.08254 36.8633 2.06173 37.6083 1.30908C38.3532 0.556438 39.3636 0.133606 40.4172 0.133606H46.3757C49.0096 0.133606 51.5356 1.19069 53.398 3.0723C55.2604 4.95392 56.3067 7.50593 56.3067 10.1669V16.1869C56.3067 17.2513 55.8882 18.2721 55.1432 19.0248C54.3982 19.7774 53.3879 20.2003 52.3343 20.2003C51.2808 20.2003 50.2704 19.7774 49.5254 19.0248C48.7805 18.2721 48.3619 17.2513 48.3619 16.1869V10.1669C48.3619 9.63474 48.1527 9.12434 47.7802 8.74801C47.4077 8.37169 46.9025 8.16027 46.3757 8.16027H40.4172C39.3636 8.16027 38.3532 7.73744 37.6083 6.98479C36.8633 6.23215 36.4448 5.21134 36.4448 4.14694ZM4.66574 36.2536C5.71928 36.2536 6.72967 36.6764 7.47464 37.4291C8.2196 38.1817 8.63812 39.2025 8.63812 40.2669V46.2869C8.63812 46.8191 8.84738 47.3295 9.21986 47.7059C9.59235 48.0822 10.0975 48.2936 10.6243 48.2936H16.5829C17.6364 48.2936 18.6468 48.7164 19.3918 49.4691C20.1367 50.2217 20.5553 51.2425 20.5553 52.3069C20.5553 53.3713 20.1367 54.3921 19.3918 55.1448C18.6468 55.8974 17.6364 56.3203 16.5829 56.3203H10.6243C7.99046 56.3203 5.46448 55.2632 3.60207 53.3816C1.73965 51.5 0.693359 48.9479 0.693359 46.2869V40.2669C0.693359 39.2025 1.11188 38.1817 1.85684 37.4291C2.60181 36.6764 3.6122 36.2536 4.66574 36.2536ZM52.3343 36.2536C53.3879 36.2536 54.3982 36.6764 55.1432 37.4291C55.8882 38.1817 56.3067 39.2025 56.3067 40.2669V46.2869C56.3067 48.9479 55.2604 51.5 53.398 53.3816C51.5356 55.2632 49.0096 56.3203 46.3757 56.3203H40.4172C39.3636 56.3203 38.3532 55.8974 37.6083 55.1448C36.8633 54.3921 36.4448 53.3713 36.4448 52.3069C36.4448 51.2425 36.8633 50.2217 37.6083 49.4691C38.3532 48.7164 39.3636 48.2936 40.4172 48.2936H46.3757C46.9025 48.2936 47.4077 48.0822 47.7802 47.7059C48.1527 47.3295 48.3619 46.8191 48.3619 46.2869V40.2669C48.3619 39.2025 48.7805 38.1817 49.5254 37.4291C50.2704 36.6764 51.2808 36.2536 52.3343 36.2536Z" fill="#5271FF"/>
          </svg>
          </div>
        </button>
      </form>
    </div>
    
    <!-- Custom Popup untuk Face ID -->
    <div id="faceIdPopup" class="popup-overlay fixed inset-0 bg-opacity-50 flex items-center justify-center z-[9999] mb-150" style="display: none;">
      <div class="popup-content bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl relative">
        <div>
          <div class="mb-4 flex text-center justify-between">
            <p class="font-bold mb-0 flex-1 pr-3 leading-snug">
            You haven’t Registered your Face ID yet
          </p>
          <!-- Tombol Close -->
          <button onclick="closeFaceIdPopup()" aria-label="Close popup" 
            class="text-gray-600 hover:text-gray-900 focus:outline-none text-2xl font-bold rounded-full leading-none">
            &times;
          </button>
        </div>
        <div class="flex justify-center">
          <button onclick="redirectToFaceId()" 
            class="px-6 py-2 bg-blue-500 text-white hover:bg-pink-600 transition-colors font-semibold h-8 w-40 text-xs rounded-3xl shadow-lg ">
            Regist Now
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Simple Face ID Check -->
  <script>
    // Fungsi utama untuk cek Face ID
    function checkFaceID() {
      const hasFaceID = localStorage.getItem('face_id');
      if (!hasFaceID) {
        showFaceIdPopup();
      } else {
        window.location.href = "{{ url('/punch-in/scan') }}";
      }
    }
  
    // Fungsi untuk menampilkan popup
    function showFaceIdPopup() {
      const popup = document.getElementById('faceIdPopup');
      popup.style.display = 'flex';
      // Jika ingin animasi, tambahkan class show setelah sedikit delay
      setTimeout(() => {
        popup.classList.add('show');
      }, 10);
    }
  
    // Fungsi untuk redirect ke halaman pendaftaran Face ID
    function redirectToFaceId() {
      window.location.href = "{{ url('/face-verification') }}";
    }
  
    // (Opsional) Fungsi untuk menutup popup jika ingin menambahkan tombol close
    function closeFaceIdPopup() {
      const popup = document.getElementById('faceIdPopup');
      popup.classList.remove('show');
      setTimeout(() => {
        popup.style.display = 'none';
      }, 300); // Sesuaikan dengan durasi animasi CSS jika ada
    }
    function closeFaceIdPopup() {
      const popup = document.getElementById('faceIdPopup');
      popup.classList.remove('show');
        setTimeout(() => {
          popup.style.display = 'none';
        }, 300);
    }
  </script>
  
  <!-- Tambahkan di akhir sebelum </body> -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;

        // Tampilkan lokasi di form tersembunyi
        document.querySelector('input[name="latitude"]').value = userLat;
        document.querySelector('input[name="longitude"]').value = userLng;

        // Inisialisasi peta
        const map = L.map('map').setView([userLat, userLng], 16);

        // Tambahkan layer tile gratis dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker lokasi user
        L.marker([userLat, userLng]).addTo(map)
          .bindPopup('Lokasi Kamu Sekarang')
          .openPopup();

        // Lokasi kantor atau titik referensi tetap (misalnya IBI Kesatuan)
        const targetLat = -6.596300; // contoh koordinat IBI Kesatuan
        const targetLng = 106.806039;

        L.marker([targetLat, targetLng]).addTo(map)
          .bindPopup('IBI Kesatuan')
          .openPopup();

      }, function (error) {
        alert("Gagal mengambil lokasi: " + error.message);
      });
    } else {
      alert("Browser tidak mendukung Geolocation.");
    }
  });
  </script>
  </body>
</html>
