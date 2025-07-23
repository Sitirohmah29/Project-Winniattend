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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body class="mt-15 bg-white font-poppins">
    {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
            onclick="window.location.href='{{ url('/indexReport') }}'"></i>
        <h2 class="text-title text-center w-full">
            {{ \Carbon\Carbon::parse($attendance->date)->format('d M l') }}
        </h2>
    </div>

    {{-- Details Report --}}
    <div class=" bg-white shadow rounded-md">
        <p class="text-xl font-semibold text-blue-600 mb-2 ml-5"> Details </p>

        <div class="w-full space-y-4 px-5 ">
            <!-- bagian map -->
            <div id="map" class="grid-con-rounded shadow-2xl" style="min-height: 200px;">
                <div class="absolute bottom-5 left-2 bg-opacity-90 rounded-md shadow p-3 z-[500] max-w-xs">
                    <h2 class="text-sm font-semibold mb-2">Available</h2>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                                <div class="h-2 w-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold">
                                {{ $attendance->check_in_location ?? 'Lokasi Tidak Tersedia' }}
                            </h3>
                            <p class="text-xs font-thin">
                                {{ $attendance->check_in_location ?? 'Alamat tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                {{-- working hours report  --}}
                <div class="grid-con-rounded shadow-2xl">

                    {{-- check in --}}
                    <span>
                        <p class="text-reg">Check in</p>
                        <p class="text-title">
                            {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}
                        </p>
                    </span>
                    <span>
                        <p class="text-reg">Check out</p>
                        <p class="text-title">
                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                        </p>
                    </span>
                    <span class="grid justify-end">
                        <p class="text-reg">Total Working hours</p>
                        <p class="text-big">{{ $workHours }}</p>
                    </span>
                    {{-- ... --}}
                    <p class="text-reg">Face ID</p>
                    <div class="flex-container justify-center items-center">
                        <p class="text-title2">
                            @if ($attendance->check_in)
                                Success
                            @elseif ($attendance->permission)
                                Permission
                            @else
                                {{ ucfirs($attendance->status_label) }}
                            @endif
                        </p>

                        @if ($attendance->check_in)
                            <i class="fa-solid fa-circle-check fa-lg" style="color: #63E6BE;"></i>
                        @elseif($attendance->permission)
                            <i class="fa-solid fa-circle-exclamation fa-lg" style="color: #FF66C4;"></i>
                        @else
                            <i class="fa-solid fa-circle-xmark fa-lg" style="color: #FF0000;"></i>
                        @endif
                    </div>

                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const mapContainer = document.getElementById("map");

                            // Ambil lokasi attendance dari backend
                            const attendanceLat = {{ $attendance->latitude ?? -6.5963 }};
                            const attendanceLng = {{ $attendance->longitude ?? 106.806039 }};

                            if (mapContainer && mapContainer.offsetHeight > 0) {
                                // Inisialisasi peta pada lokasi attendance
                                const map = L.map('map').setView([attendanceLat, attendanceLng], 16);

                                // Tile layer
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);

                                // Marker lokasi attendance
                                L.marker([attendanceLat, attendanceLng]).addTo(map)
                                    .bindPopup('Lokasi saat absen')
                                    .openPopup();

                                // (Optional) Marker lokasi kantor
                                const targetLat = -6.596300;
                                const targetLng = 106.806039;
                                L.marker([targetLat, targetLng]).addTo(map)
                                    .bindPopup('IBI Kesatuan');
                            } else {
                                console.error("Elemen #map tidak memiliki ukuran yang cukup untuk menampilkan peta.");
                            }
                        });
                    </script>
</body>

</html>
