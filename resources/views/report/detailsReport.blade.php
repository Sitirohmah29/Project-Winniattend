<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details Report</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /><script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body class="mt-15 bg-white font-poppins">
     {{-- information page --}}
     <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/indexReport')}}'"></i>
        <h2 class="text-title text-center w-full">01 Feb Monday</h2>
    </div>

    {{-- Details Report --}}
    <div class=" bg-white shadow  px-4 rounded-md">
        <p class="text-xl font-semibold text-blue-600 mb-2"> Details </p>
    
        <div class="w-full space-y-4 ">
            <!-- bagian map -->
            <div id="map" class="w-full rounded-md relative p-10" style="min-height: 200px;">
                <div class="absolute bottom-5 left-2 bg-opacity-90 rounded-md shadow p-3 z-[500] max-w-xs">
                    <h2 class="text-sm font-semibold mb-2">Available</h2>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold">IBI Kesatuan</h3>
                            <p class="text-xs font-thin">Jl. Ranggagading no.1 RT.02/09, Gudang, Kec.Bogor Tengah, Bogor, Indonesia 16123
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
            {{-- working hours report  --}}
            <div class="grid-con-rounded shadow-2xl ">

            {{-- check in --}}
            <span>
                <p class="text-reg">Check in</p>
                <p class="text-title">01.00 am</p>
            </span>

            {{-- check out --}}
            <span>
                <p class="text-reg">Check out</p>
                <p class="text-title">06.00 am</p>
            </span>

            {{-- total working hours --}}
            <span class="grid justify-end">
                <p class="text-reg">Total Working hours</p>
                <p class="text-big">4 Hours</p>
            </span>
        </div>

        {{-- face id report --}}
        <div class="grid-con-rounded shadow-2xl ">
            <p class="text-reg">Face ID</p>
            <div class="flex-container justify-center items-center">
                <p class="text-title2">Success Complete</p>
                <i class="fa-solid fa-circle-check fa-lg" style="color: #63E6BE;"></i>
            </div>
        </div>
    </div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
  const mapContainer = document.getElementById("map");

  // Pastikan elemen map memiliki tinggi
  if (mapContainer && mapContainer.offsetHeight > 0) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;

        // Inisialisasi peta
        const map = L.map('map').setView([userLat, userLng], 16);

        // Tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker user
        L.marker([userLat, userLng]).addTo(map)
          .bindPopup('Lokasi Kamu Sekarang')
          .openPopup();

        // Marker lokasi target
        const targetLat = -6.596300;
        const targetLng = 106.806039;

        L.marker([targetLat, targetLng]).addTo(map)
          .bindPopup('IBI Kesatuan');

      }, function (error) {
        alert("Gagal mengambil lokasi: " + error.message);
      });
    } else {
      alert("Browser tidak mendukung Geolocation.");
    }
  } else {
    console.error("Elemen #map tidak memiliki ukuran yang cukup untuk menampilkan peta.");
  }
});

</script>
</body>
</html>
