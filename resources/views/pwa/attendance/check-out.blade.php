<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#ffffff" />
  <title>WinniAttend - Check Out</title>

  <!-- PWA Meta Tags -->
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="WinniAttend" />
  <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}" />
  <link rel="manifest" href="{{ asset('/manifest.json') }}" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <!-- Leaflet CSS and JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <style>
    .popup-overlay {
      transition: all 0.3s ease;
    }
    .popup-overlay.show {
      display: flex !important;
    }
    .popup-content {
      transform: scale(0.8) translateY(-20px);
      transition: all 0.3s ease;
    }
    .popup-overlay.show .popup-content {
      transform: scale(1) translateY(0);
    }
  </style>
</head>

<body class="bg-white font-sans">
  <div class="flex flex-col h-screen">
    <!-- Header -->
    <div class="px-4 py-3 bg-white shadow">
      <div class="flex items-center">
        <a href="{{ url('dashboard') }}" class="mr-4">
          <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600"></i>
        </a>
        <h1 class="text-lg font-semibold text-center flex-1">Check Out</h1>
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

    <!-- Face ID Button -->
    <div class="flex justify-center mt-8">
      <form id="punchOutForm" method="POST" action="{{route('attendance.realtime')}}" enctype="multipart/form-data" class="flex flex-col items-center">
        @csrf
        <input type="hidden" name="punch_out_location" value="IBI Kesatuan, Bogor" />
        <input type="hidden" name="latitude" />
        <input type="hidden" name="longitude" />
        <input type="hidden" name="shift" value="Frontend Developer" />
        <input type="hidden" name="in_allowed_range" value="0" />
        <input type="hidden" name="checkout_time" id="checkout_time"/>

        <button type="button" id="scanFaceBtn" onclick="doFaceCheckOut()" class="w-32 h-32 flex items-center justify-center hover:scale-105 transition-transform duration-200">
          <div class="flex items-center justify-center">
            <svg
              width="123"
              height="124"
              viewBox="0 0 123 124"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              onmouseover="this.children[0].setAttribute('fill', '#FF66C4')"
              onmouseout="this.children[0].setAttribute('fill', '#5271FF')"
            >
              <circle cx="61.5" cy="61.7269" r="61.5" fill="#5271FF"/>
              <circle cx="62" cy="62.2269" r="48" fill="#F5FAFF"/>
              <g clip-path="url(#clip0_2514_308)">
                <path d="M79.3539 85.4769V75.5614C79.3539 74.3788 79.1209 73.2078 78.6684 72.1152C78.2158 71.0226 77.5525 70.0298 76.7162 69.1936C75.88 68.3573 74.8872 67.694 73.7946 67.2414C72.702 66.7888 71.531 66.5559 70.3484 66.5559H62.5294V51.9399C62.5294 49.6159 60.6429 47.7329 58.3224 47.7329C55.9984 47.7329 54.1119 49.6159 54.1119 51.9399V71.0989L52.7119 71.3614C51.7289 71.5457 50.8076 71.9726 50.0316 72.6035C49.2556 73.2343 48.6495 74.0491 48.2684 74.9737C47.8873 75.8983 47.7432 76.9035 47.8493 77.8979C47.9554 78.8923 48.3083 79.8445 48.8759 80.6679L49.4394 81.4869L52.1834 85.4769" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M46.0897 55.4889C45.6583 53.6352 45.6511 51.708 46.0687 49.8511C46.4862 47.9943 47.3178 46.2557 48.5013 44.7651C49.6847 43.2746 51.1896 42.0706 52.9034 41.2429C54.6173 40.4153 56.496 39.9855 58.3992 39.9855C60.3025 39.9855 62.1812 40.4153 63.895 41.2429C65.6089 42.0706 67.1138 43.2746 68.2972 44.7651C69.4807 46.2557 70.3123 47.9943 70.7298 49.8511C71.1474 51.708 71.1402 53.6352 70.7087 55.4889" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
              </g>
              <defs>
                <clipPath id="clip0_2514_308">
                  <rect width="49" height="49" fill="white" transform="translate(37 38.2269)"/>
                </clipPath>
              </defs>
            </svg>
          </div>
        </button>

        <div class="mt-4 text-center">
          <p class="text-sm font-semibold">Check Out</p>
        </div>
        <p class="text-sm font-semibold" id="realTimeClock"></p>
      </form>
    </div>
  </div>

  <!-- Custom Popup -->
  <div id="checkOutPopup" class="popup-overlay fixed inset-0 bg-opacity-50 flex items-center justify-center z-[9999]">
    <div class="popup-content bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
      <div class="text-center">
        <div class="mb-4">
            <i class="fa-solid fa-check-circle text-4xl text-blue-500 mb-3"></i>
            <p class="text-gray-800 mb-3">You go home at</p>
            <p id="checkOutTime" class="text-xl font-bold text-blue-600"></p>
        </div>
        <div class="flex justify-center">
          <button onclick="closePopup()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-semibold">
            OK
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Pastikan popup tersembunyi saat load
      const popup = document.getElementById('checkOutPopup');
      popup.style.display = 'none';
      popup.classList.remove('show');

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

    function updateClock() {
      // Update jam real time di halaman dan input hidden
      const now = new Date();

      // Format: YYYY-MM-DD HH:mm:ss (misal: 2025-05-30 18:45:12)
      const pad = (n) => n.toString().padStart(2, '0');
      const formatted =
        `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

      document.getElementById('realTimeClock').textContent = formatted;
      document.getElementById('checkout_time').value = formatted;
    }
    setInterval(updateClock, 1000);
    updateClock();

//logic show pop up
    function showCheckOutPopup() {
      const now = new Date();
      const pad = (n) => n.toString().padStart(2, '0');
      const timeString = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

      // Update waktu di popup
      document.getElementById('checkOutTime').textContent = timeString;

      // Tampilkan popup
      const popup = document.getElementById('checkOutPopup');
      popup.style.display = 'flex';
      setTimeout(() => {
        popup.classList.add('show');
      }, 10);
    }

    function doFaceCheckOut() {
    // Ambil data dari form
    const userId = {{ Auth::id() }};
    const latitude = document.querySelector('input[name="latitude"]').value;
    const longitude = document.querySelector('input[name="longitude"]').value;
    const checkout_time = document.getElementById('checkout_time').value;

    fetch('{{ route("attendance.face-verification-checkout") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: userId,
            latitude: latitude,
            longitude: longitude,
            checkout_time: checkout_time
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showCheckOutPopup();
        } else {
            alert(data.message || 'Check out gagal!');
        }
    })
    .catch(() => alert('Gagal mengirim data check out!'));
}

//logic close pop up
    function closePopup() {
      // Tutup popup dan kembali ke tampilan attendance
      const popup = document.getElementById('checkOutPopup');
      popup.classList.remove('show');
      setTimeout(() => {
        popup.style.display = 'none';
      }, 300);
    }

    // Tutup popup dengan ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closePopup();
      }
    });
  </script>
</body>
</html>
