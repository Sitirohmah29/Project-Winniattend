const CACHE_NAME = "winniattend-v1";
const urlsToCache = [
    "/",
    "/css/app.css",
    "/js/app.js",
    "/images/logo.svg",
    "/login",
    "/offline",
    "/offline.html", // Tambahkan offline.html ke cache
];

// Install a service worker
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
});

// Cache and return requests
self.addEventListener("fetch", (event) => {
    // Hanya cache request http(s)
    if (!event.request.url.startsWith("http")) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then((response) => {
            // Cache hit - return response
            if (response) {
                return response;
            }
            return fetch(event.request)
                .then((response) => {
                    // Check if we received a valid response
                    if (
                        !response ||
                        response.status !== 200 ||
                        response.type !== "basic"
                    ) {
                        return response;
                    }

                    // Clone the response
                    const responseToCache = response.clone();

                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });

                    return response;
                })
                .catch(() => {
                    // Jika fetch gagal dan mode navigate, tampilkan offline.html
                    if (event.request.mode === "navigate") {
                        return caches.match("/offline.html");
                    }
                    // Jika request asset, fallback ke offline.html juga
                    if (
                        event.request.destination === "document" ||
                        event.request.destination === "image" ||
                        event.request.destination === "style" ||
                        event.request.destination === "script"
                    ) {
                        return caches.match("/offline.html");
                    }
                });
        })
    );
});

// Update service worker
self.addEventListener("activate", (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
