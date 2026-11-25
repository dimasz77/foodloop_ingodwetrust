/* FoodLoop service worker: halaman offline + cache aset statis. */
const VERSION = 'foodloop-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE = [OFFLINE_URL, '/favicon.svg', '/icons/icon-192.png', '/manifest.webmanifest'];
const STATIC_HOSTS = ['cdn.tailwindcss.com', 'cdn.jsdelivr.net', 'unpkg.com', 'fonts.googleapis.com', 'fonts.gstatic.com'];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(VERSION).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((key) => key !== VERSION).map((key) => caches.delete(key))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;
  if (request.method !== 'GET') {
    return;
  }
  const url = new URL(request.url);

  // Halaman: selalu ambil versi terbaru, tampilkan halaman offline jika jaringan putus.
  if (request.mode === 'navigate') {
    event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
    return;
  }

  // Aset statis (CDN, font, ikon): cache dulu agar cepat, perbarui di latar belakang.
  if (STATIC_HOSTS.includes(url.hostname) || url.pathname.startsWith('/icons/') || url.pathname === '/favicon.svg') {
    event.respondWith(
      caches.open(VERSION).then(async (cache) => {
        const cached = await cache.match(request);
        const network = fetch(request).then((response) => {
          if (response.ok || response.type === 'opaque') {
            cache.put(request, response.clone());
          }
          return response;
        }).catch(() => cached);
        return cached || network;
      })
    );
  }
});
