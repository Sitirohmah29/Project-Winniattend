<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#ffffff" />
  <title>WinniAttend - Punch In</title>

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

  <!-- Leaflet CSS and JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class=" font-poppins">
  <div class="flex flex-col h-screen">
    <!-- Header -->
    <div class="px-4 py-3  shadow">
      <div class="flex items-center">
        <a href="{{ url('dashboard') }}" class="mr-4">
          <i class="fas fa-chevron-left text-gray-600"></i>
        </a>
        <h1 class="text-sm font-semibold text-center flex-1">Punch in</h1>
      </div>
    </div>

    <!-- Map Container -->
    <div id="map" class="w-full h-80 rounded-md relative">
      <div class="absolute bottom-5 left-2 bg-opacity-90 rounded-md shadow p-3 z-[500] max-w-xs">
        <h2 class="text-sm font-semibold mb-2">Available</h2>
        <div id="selected-location">
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                <div class="h-2 w-2 rounded-full"></div>
              </div>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-semibold" id="selected-location-name">IBI Kesatuan</h3>
              <p class="text-xs font-thin" id="selected-location-address">
                Jl. Ranggagading no.1 RT.02/09, Gudang, Kec.Bogor Tengah, Bogor, Indonesia 16123
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Shift Information -->
    <div class="px-4 py-3">
      <h2 class="text-sm font-semibold mb-2">Shift</h2>
      <div class="bg-white p-1">
        <div class="flex items-center">
          <div class="flex-shrink-0 ">
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
      <form id="punchInForm" action="" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
        @csrf
        <input type="hidden" name="punch_in_location" id="punch_in_location" value="IBI Kesatuan, Bogor" />
        <input type="hidden" name="latitude" id="latitude" />
        <input type="hidden" name="longitude" id="longitude" />
        <input type="hidden" name="shift" value="Frontend Developer" />
        <input type="hidden" name="punch_in_photo" id="punch_in_photo_input" />
        <input type="hidden" name="in_allowed_range" id="in_allowed_range" value="0" />

        <button type="button" id="scanFaceBtn" onclick="startFaceScan()" class="w-25 h-25 rounded-full bg-white shadow-lg flex flex-col items-center justify-center"
        disabled>
        <div class="w-15 h-15">
          <svg width="100%" height="100%" viewBox="0 0 57 57" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8.6377 46.287C8.6377 44.6904 9.26547 43.1592 10.3829 42.0302C11.5004 40.9012 13.016 40.267 14.5963 40.267H42.4029C43.9832 40.267 45.4988 40.9012 46.6163 42.0302C47.7337 43.1592 48.3615 44.6904 48.3615 46.287V52.307H8.6377V46.287Z" fill="#38B6FF"/>
            <path d="M39.4243 23.2103C39.4243 20.2832 38.2733 17.476 36.2247 15.4062C34.176 13.3364 31.3975 12.1736 28.5002 12.1736C25.603 12.1736 22.8244 13.3364 20.7758 15.4062C18.7271 17.476 17.5762 20.2832 17.5762 23.2103C17.5762 26.1374 18.7271 28.9446 20.7758 31.0144C22.8244 33.0842 25.603 34.247 28.5002 34.247C31.3975 34.247 34.176 33.0842 36.2247 31.0144C38.2733 28.9446 39.4243 26.1374 39.4243 23.2103Z" fill="#5271FF"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6243 8.16027C10.0975 8.16027 9.59235 8.37169 9.21986 8.74801C8.84738 9.12434 8.63812 9.63474 8.63812 10.1669V16.1869C8.63812 17.2513 8.2196 18.2721 7.47464 19.0248C6.72967 19.7774 5.71928 20.2003 4.66574 20.2003C3.6122 20.2003 2.60181 19.7774 1.85684 19.0248C1.11188 18.2721 0.693359 17.2513 0.693359 16.1869V10.1669C0.693359 7.50593 1.73965 4.95392 3.60207 3.0723C5.46448 1.19069 7.99046 0.133606 10.6243 0.133606H16.5829C17.6364 0.133606 18.6468 0.556438 19.3918 1.30908C20.1367 2.06173 20.5553 3.08254 20.5553 4.14694C20.5553 5.21134 20.1367 6.23215 19.3918 6.98479C18.6468 7.73744 17.6364 8.16027 16.5829 8.16027H10.6243ZM36.4448 4.14694C36.4448 3.08254 36.8633 2.06173 37.6083 1.30908C38.3532 0.556438 39.3636 0.133606 40.4172 0.133606H46.3757C49.0096 0.133606 51.5356 1.19069 53.398 3.0723C55.2604 4.95392 56.3067 7.50593 56.3067 10.1669V16.1869C56.3067 17.2513 55.8882 18.2721 55.1432 19.0248C54.3982 19.7774 53.3879 20.2003 52.3343 20.2003C51.2808 20.2003 50.2704 19.7774 49.5254 19.0248C48.7805 18.2721 48.3619 17.2513 48.3619 16.1869V10.1669C48.3619 9.63474 48.1527 9.12434 47.7802 8.74801C47.4077 8.37169 46.9025 8.16027 46.3757 8.16027H40.4172C39.3636 8.16027 38.3532 7.73744 37.6083 6.98479C36.8633 6.23215 36.4448 5.21134 36.4448 4.14694ZM4.66574 36.2536C5.71928 36.2536 6.72967 36.6764 7.47464 37.4291C8.2196 38.1817 8.63812 39.2025 8.63812 40.2669V46.2869C8.63812 46.8191 8.84738 47.3295 9.21986 47.7059C9.59235 48.0822 10.0975 48.2936 10.6243 48.2936H16.5829C17.6364 48.2936 18.6468 48.7164 19.3918 49.4691C20.1367 50.2217 20.5553 51.2425 20.5553 52.3069C20.5553 53.3713 20.1367 54.3921 19.3918 55.1448C18.6468 55.8974 17.6364 56.3203 16.5829 56.3203H10.6243C7.99046 56.3203 5.46448 55.2632 3.60207 53.3816C1.73965 51.5 0.693359 48.9479 0.693359 46.2869V40.2669C0.693359 39.2025 1.11188 38.1817 1.85684 37.4291C2.60181 36.6764 3.6122 36.2536 4.66574 36.2536ZM52.3343 36.2536C53.3879 36.2536 54.3982 36.6764 55.1432 37.4291C55.8882 38.1817 56.3067 39.2025 56.3067 40.2669V46.2869C56.3067 48.9479 55.2604 51.5 53.398 53.3816C51.5356 55.2632 49.0096 56.3203 46.3757 56.3203H40.4172C39.3636 56.3203 38.3532 55.8974 37.6083 55.1448C36.8633 54.3921 36.4448 53.3713 36.4448 52.3069C36.4448 51.2425 36.8633 50.2217 37.6083 49.4691C38.3532 48.7164 39.3636 48.2936 40.4172 48.2936H46.3757C46.9025 48.2936 47.4077 48.0822 47.7802 47.7059C48.1527 47.3295 48.3619 46.8191 48.3619 46.2869V40.2669C48.3619 39.2025 48.7805 38.1817 49.5254 37.4291C50.2704 36.6764 51.2808 36.2536 52.3343 36.2536Z" fill="#5271FF"/>
          </svg>
        </div>
      </button>
      <span class="text-xs font-semibold mt-2" id="scan-text">Scan Face ID</span>
      </form>
    </div>

    <!-- Status Indicator -->
    <div id="status-indicator" class="fixed bottom-24 left-0 right-0 text-center p-2 hidden">
      <div class="bg-red-500 text-white rounded-lg mx-auto py-2 px-4 inline-block">
        <span id="status-message">You are not in attendance area</span>
      </div>
    </div>

    <!-- Face Capture Modal -->
    <div id="camera-container" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
      <div class="bg-white p-4 rounded-lg w-full max-w-md">
        <h3 class="text-lg font-medium mb-2">Face ID Verification</h3>
        <div id="camera" class="w-full h-64 bg-gray-200 mb-4 relative overflow-hidden">
          <video id="video" class="w-full h-full object-cover" autoplay></video>
          <canvas id="canvas" class="hidden"></canvas>
        </div>
        <div class="flex justify-between">
          <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-200 rounded-md text-gray-800">Cancel</button>
          <button type="button" id="captureBtn" class="px-4 py-2 bg-blue-500 rounded-md text-white">Capture</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Pop-up Modal -->
<div
x-data="{ showModal: false }"
x-show="showModal"
x-cloak
x-transition
class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
>
<div class="bg-white p-6 rounded-lg shadow-lg w-72 text-center">
  <h2 class="text-lg font-bold mb-2">Wajah Belum Terdaftar</h2>
  <p class="text-sm mb-4">Silakan registrasi wajah terlebih dahulu untuk menggunakan Face ID.</p>
  <button @click="showModal = false" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
    Tutup
  </button>
</div>
</div>
<script>
  function startFaceScan() {
    // Cek jika wajah belum diregistrasi, tampilkan pop-up
    document.querySelector('[x-data]').__x.$data.showModal = true;

    // Jika nanti ingin lanjut ke face scan setelah registrasi,
    // bisa tambahkan else dan logic scanning di sini.
  }
</script>

  <!-- Map Initialization Script -->
  <script>
    // Define office locations with coordinates
    const officeLocations = [
      {
        name: "IBI Kesatuan",
        address: "Jl. Ranggagading no.1 RT.02/09, Gudang, Kec.Bogor Tengah, Bogor, Indonesia 16123",
        position: [-6.597825, 106.793898], // [lat, lng] format for Leaflet
        allowedRadius: 100 // in meters
      },
      {
        name: "Head Office",
        address: "Jl. Pahlawan No. 123, Jakarta Pusat, Indonesia 10110",
        position: [-6.175110, 106.865036], // [lat, lng] format for Leaflet
        allowedRadius: 100 // in meters
      }
    ];

    let map, userMarker, userCircle, officeMarkers = [], userPosition;

    document.addEventListener('DOMContentLoaded', function() {
      initMap();
    });

    function initMap() {
      // Initialize the map with a temporary default view that will be updated with user's position
      map = L.map('map');

      // Try to get user's position first before setting the view
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            map.setView([userLat, userLng], 16);
          },
          function(error) {
            console.error('Error getting initial position:', error);
            // Fallback to default office position if geolocation fails
            map.setView(officeLocations[0].position, 16);
          }
        );
      } else {
        // Fallback if geolocation is not supported
        map.setView(officeLocations[0].position, 16);
      }

      // Add OpenStreetMap tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
      }).addTo(map);

      // Add office markers and circles
      officeLocations.forEach(office => {
        // Create custom icon for office using Tailwind classes
        const officeIcon = L.divIcon({
          html: `<div class="relative">
                  <div class="absolute -left-3 -top-3 w-6 h-6 bg-red-500 rounded-tl-full rounded-tr-full rounded-bl-none rounded-br-full rotate-45 flex items-center justify-center">
                    <div class="w-4 h-4 bg-white rounded-full rotate-45"></div>
                  </div>
                </div>`,
          className: '',
          iconSize: [24, 24],
          iconAnchor: [12, 24]
        });

        // Add marker for office
        const marker = L.marker(office.position, {
          icon: officeIcon,
          title: office.name
        }).addTo(map);

        // Add circle to show allowed radius
        const circle = L.circle(office.position, {
          color: 'red',
          fillColor: '#f03',
          fillOpacity: 0.15,
          radius: office.allowedRadius
        }).addTo(map);

        // Add popup with office info
        marker.bindPopup(`<b>${office.name}</b><br>${office.address}`);

        officeMarkers.push({ marker, circle, info: office });
      });

      const statusIndicator = document.getElementById('status-indicator');
      const statusMessage = document.getElementById('status-message');
      const scanBtn = document.getElementById('scanFaceBtn');
      const scanText = document.getElementById('scan-text');

      // Show loading indicator while getting location
      const loadingDiv = document.createElement('div');
      loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-70 z-10';
      loadingDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i><p class="mt-2 text-gray-700">Getting your location...</p></div>';
      document.getElementById('map').appendChild(loadingDiv);

      // Get user location and track it
      if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(position) {
          // Remove loading indicator once we have the position
          if (loadingDiv.parentNode) {
            loadingDiv.parentNode.removeChild(loadingDiv);
          }
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          userPosition = [lat, lng];

          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lng;

          // Create or update user marker
          if (!userMarker) {
            // Create blue marker for user
            const userIcon = L.divIcon({
              html: `<div class="relative">
                      <div class="absolute -left-3 -top-3 w-6 h-6 bg-blue-500 rounded-tl-full rounded-tr-full rounded-bl-none rounded-br-full rotate-45 flex items-center justify-center">
                        <div class="w-4 h-4 bg-white rounded-full rotate-45"></div>
                      </div>
                    </div>`,
              className: '',
              iconSize: [24, 24],
              iconAnchor: [12, 24]
            });

            userMarker = L.marker(userPosition, {
              icon: userIcon,
              title: "Your Location"
            }).addTo(map);

            userMarker.bindPopup("You are here").openPopup();

            // Center map on user with animation
            map.flyTo(userPosition, 16, {
              animate: true,
              duration: 1
            });
          } else {
            userMarker.setLatLng(userPosition);

            // Re-center map on user position when location updates
            // Only if the "center-on-me" setting is active
            if (window.centerOnUserEnabled) {
              map.panTo(userPosition);
            }
          }

          // Create a "Center on me" button if it doesn't exist yet
          if (!window.centerButton) {
            const centerButton = L.control({position: 'bottomright'});
            centerButton.onAdd = function() {
              const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
              div.innerHTML = '<button class="bg-white w-10 h-10 flex items-center justify-center rounded-md shadow-md focus:outline-none" id="center-on-me"><i class="fas fa-location-arrow text-blue-500"></i></button>';
              return div;
            };
            centerButton.addTo(map);

            window.centerOnUserEnabled = true;
            window.centerButton = true;

            // Add click event listener to the button after it's added to the map
            setTimeout(() => {
              document.getElementById('center-on-me').addEventListener('click', function() {
                window.centerOnUserEnabled = true;
                if (userPosition) {
                  map.flyTo(userPosition, 16, {animate: true, duration: 0.5});
                }
              });

              // Disable automatic centering when user manually pans the map
              map.on('dragstart', function() {
                window.centerOnUserEnabled = false;
              });
            }, 100);
          }

          // Check distance to all office locations and find the closest one
          let closestOffice = null;
          let minDistance = Infinity;
          let inAllowedRange = false;

          officeLocations.forEach(office => {
            const distance = calculateDistance(
              lat, lng,
              office.position[0], office.position[1]
            );

            if (distance < minDistance) {
              minDistance = distance;
              closestOffice = office;

              // Check if user is within allowed radius
              if (distance <= office.allowedRadius / 1000) { // Convert meters to km
                inAllowedRange = true;
              }
            }
          });

          // Update UI with closest office info
          if (closestOffice) {
            document.getElementById('selected-location-name').textContent = closestOffice.name;
            document.getElementById('selected-location-address').textContent = closestOffice.address;
            document.getElementById('punch_in_location').value = closestOffice.name + ", " + closestOffice.address.split(',')[2].trim();

            const distanceText = minDistance < 1 ?
              `${(minDistance * 1000).toFixed(0)} meters away` :
              `${minDistance.toFixed(2)} km away`;
            document.getElementById('distance-info').textContent = distanceText;

            // Update status indicator
            if (inAllowedRange) {
              statusIndicator.classList.remove('hidden');
              statusIndicator.firstElementChild.classList.remove('bg-red-500');
              statusIndicator.firstElementChild.classList.add('bg-green-500');
              statusMessage.textContent = 'You are in the attendance area';
              scanBtn.disabled = false;
              scanText.textContent = 'Scan Face ID';
              document.getElementById('in_allowed_range').value = '1';
            } else {
              statusIndicator.classList.remove('hidden');
              statusIndicator.firstElementChild.classList.remove('bg-green-500');
              statusIndicator.firstElementChild.classList.add('bg-red-500');
              statusMessage.textContent = 'You are not in the attendance area';
              scanBtn.disabled = true;
              scanText.textContent = 'Out of range';
              document.getElementById('in_allowed_range').value = '0';
            }
          }
        }, function(error) {
          console.error('Geolocation error:', error);
          alert('Error getting your location: ' + error.message);
        }, {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0
        });
      } else {
        alert("Geolocation is not supported by your browser.");
      }
    }

    // Haversine formula to calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Radius of the earth in km
      const dLat = deg2rad(lat2 - lat1);
      const dLon = deg2rad(lon2 - lon1);
      const a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
        Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      const d = R * c; // Distance in km
      return d;
    }

    function deg2rad(deg) {
      return deg * (Math.PI/180);
    }
  </script>

  <!-- Camera & Capture Script -->
  <script>
    let stream;
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const cameraContainer = document.getElementById('camera-container');

    function startFaceScan() {
      // Only proceed if the button is not disabled
      if (document.getElementById('scanFaceBtn').disabled) {
        return;
      }

      cameraContainer.classList.remove('hidden');
      cameraContainer.classList.add('flex');

      navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(mediaStream => {
          stream = mediaStream;
          video.srcObject = stream;
        })
        .catch(err => {
          console.error('Camera error:', err);
          alert('Camera access denied or error: ' + err.message);
          cameraContainer.classList.add('hidden');
          cameraContainer.classList.remove('flex');
        });
    }

    document.getElementById('cancelBtn').addEventListener('click', () => {
      stopCamera();
      cameraContainer.classList.add('hidden');
      cameraContainer.classList.remove('flex');
    });

    document.getElementById('captureBtn').addEventListener('click', () => {
      const inAllowedRange = document.getElementById('in_allowed_range').value;

      if (inAllowedRange !== '1') {
        alert('You must be within the attendance area to punch in.');
        stopCamera();
        cameraContainer.classList.add('hidden');
        cameraContainer.classList.remove('flex');
        return;
      }

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);
      const dataURL = canvas.toDataURL('image/jpeg');

      fetch(dataURL)
        .then(res => res.blob())
        .then(blob => {
          const file = new File([blob], "face-photo.jpg", { type: "image/jpeg" });
          const formData = new FormData(document.getElementById('punchInForm'));
          formData.set('punch_in_photo', file);

          // Show loading state
          document.getElementById('captureBtn').textContent = 'Processing...';
          document.getElementById('captureBtn').disabled = true;

          fetch("", {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.href = "{{ url('dashboard') }}";
            } else {
              alert(data.message || 'Failed to punch in. Please try again.');
              stopCamera();
              cameraContainer.classList.add('hidden');
              cameraContainer.classList.remove('flex');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            stopCamera();
            cameraContainer.classList.add('hidden');
            cameraContainer.classList.remove('flex');
          });
        });
    });

    function stopCamera() {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
      }
    }

    // Handle page visibility changes to manage camera resources
    document.addEventListener('visibilitychange', () => {
      if (document.hidden && !cameraContainer.classList.contains('hidden')) {
        stopCamera();
        cameraContainer.classList.add('hidden');
        cameraContainer.classList.remove('flex');
      }
    });
  </script>
</body>
</html>
