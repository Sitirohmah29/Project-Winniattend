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
<body class="bg-white font-poppins">
  <div class="flex flex-col h-screen">
    <!-- Header -->
    <div class="px-4 py-3 bg-white shadow">
      <div class="flex items-center">
        <a href="{{ route('dashboard') }}" class="mr-4">
          <i class="fas fa-chevron-left text-gray-600"></i>
        </a>
        <h1 class="text-sm font-semibold text-center flex-1">Punch in</h1>
      </div>
    </div>
    
    <!-- Map Container -->
    <div id="map" class="w-full h-80 rounded-md relative">
      <div class="absolute bottom-5 left-2 bg-opacity-90 rounded-md shadow p-3 z-[500] max-w-xs">
        <h2 class="text-sm font-semibold">Available</h2>
        <div id="selected-location">
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <div class="h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                <div class="h-2 w-2 rounded-full bg-white"></div>
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
      <div class="bg-white rounded-lg shadow-sm border p-3">
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
    <div class="mt-auto mb-6 flex justify-center">
      <form id="punchInForm" action="{{ route('attendance.punch-in') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="punch_in_location" id="punch_in_location" value="IBI Kesatuan, Bogor" />
        <input type="hidden" name="latitude" id="latitude" />
        <input type="hidden" name="longitude" id="longitude" />
        <input type="hidden" name="shift" value="Frontend Developer" />
        <input type="hidden" name="punch_in_photo" id="punch_in_photo_input" />
        <input type="hidden" name="in_allowed_range" id="in_allowed_range" value="0" />

        <button type="button" id="scanFaceBtn" onclick="startFaceScan()" class="w-20 h-20 rounded-full bg-white shadow-lg flex flex-col items-center justify-center"
        disabled>
  <div class="text-blue-500">
    <i class="fas fa-face-id text-4xl"></i> <!-- Ganti ikon sesuai selera -->
  </div>
  <span class="text-xs mt-1" id="scan-text">Scan Face ID</span>
</button>

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
          
          fetch("{{ route('attendance.punch-in') }}", {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.href = "{{ route('dashboard') }}";
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