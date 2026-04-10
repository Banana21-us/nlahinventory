const CACHE = 'nlah-checklist-v1';

// Skip Livewire update requests and our sync API — those always need the network
const skipCache = (url) =>
    url.includes('/livewire/') ||
    url.includes('/api/maintenance/checklist/sync');

self.addEventListener('install', () => self.skipWaiting());

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (e) => {
    const req = e.request;
    if (req.method !== 'GET') return;
    if (skipCache(req.url)) return;

    const isHTML = req.headers.get('accept')?.includes('text/html');

    if (isHTML) {
        // Network-first for pages: show cached shell when offline
        e.respondWith(
            fetch(req)
                .then((res) => {
                    if (res.ok) {
                        const clone = res.clone();
                        caches.open(CACHE).then((c) => c.put(req, clone));
                    }
                    return res;
                })
                .catch(() =>
                    caches.match(req).then(
                        (cached) =>
                            cached ||
                            new Response(
                                '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Offline</title><style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#f3f4f6}.box{text-align:center;padding:2rem}.icon{font-size:3rem;margin-bottom:1rem}.title{font-size:1.25rem;font-weight:700;color:#1f2937;margin-bottom:.5rem}.msg{color:#6b7280;font-size:.875rem}</style></head><body><div class="box"><div class="icon">📡</div><div class="title">You\'re Offline</div><div class="msg">Your checklist photos were saved locally.<br>They will sync automatically when you reconnect.</div></div></body></html>',
                                { status: 503, headers: { 'Content-Type': 'text/html' } }
                            )
                    )
                )
        );
    } else {
        // Cache-first for static assets (CSS, JS, images, fonts)
        e.respondWith(
            caches.match(req).then((cached) => {
                if (cached) return cached;
                return fetch(req).then((res) => {
                    if (res.ok) {
                        const clone = res.clone();
                        caches.open(CACHE).then((c) => c.put(req, clone));
                    }
                    return res;
                });
            })
        );
    }
});
