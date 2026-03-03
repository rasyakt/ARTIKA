// ARTIKA POS - Service Worker
const CACHE_NAME = 'artika-pos-v3';
const OFFLINE_URL = '/offline.html';

// Static assets to pre-cache
const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/img/icons/icon-192x192.png',
    '/img/icons/icon-512x512.png',
    '/img/icon.png',
];

// Install: pre-cache essential assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate: clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch: network-first for pages, cache-first for static assets
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip cross-origin requests
    if (url.origin !== location.origin) return;

    // Skip API/AJAX requests (let them pass through)
    if (request.headers.get('X-Requested-With') === 'XMLHttpRequest' ||
        request.headers.get('Accept')?.includes('application/json') ||
        url.pathname.startsWith('/api/')) {
        return;
    }

    // Static assets: cache-first strategy
    if (isStaticAsset(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then((response) => {
                    if (response.ok) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                }).catch(() => {
                    // Return nothing for failed static asset fetches
                    return new Response('', { status: 408 });
                });
            })
        );
        return;
    }

    // Pages: network-first with offline fallback
    event.respondWith(
        fetch(request)
            .then((response) => {
                // Cache successful page responses
                if (response.ok) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Try to serve from cache first
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Show offline page as last resort
                    return caches.match(OFFLINE_URL);
                });
            })
    );
});

// Helper: check if request is for a static asset
function isStaticAsset(pathname) {
    const staticExtensions = [
        '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg',
        '.ico', '.woff', '.woff2', '.ttf', '.eot', '.webp'
    ];
    return staticExtensions.some((ext) => pathname.endsWith(ext)) ||
           pathname.startsWith('/build/') ||
           pathname.startsWith('/img/');
}
