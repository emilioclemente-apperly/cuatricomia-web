const CACHE_NAME = 'fedebclubes-v2';
const urlsToCache = [
  './',
  './index.php',
  './js/app.js',
  './manifest.json',
  './Imagenes/logo_canarias.svg'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);

  if (req.method === 'GET' && (url.pathname.includes('api.php') || url.pathname.endsWith('index.php') || url.pathname.endsWith('/'))) {
    event.respondWith(
      fetch(req)
        .then(networkRes => {
          const resClone = networkRes.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(req, resClone));
          return networkRes;
        })
        .catch(() => caches.match(req))
    );
  } else {
    event.respondWith(
      caches.match(req).then(cachedRes => {
        const fetchPromise = fetch(req).then(networkRes => {
          caches.open(CACHE_NAME).then(cache => cache.put(req, networkRes.clone()));
          return networkRes;
        }).catch(() => {});
        return cachedRes || fetchPromise;
      })
    );
  }
});
