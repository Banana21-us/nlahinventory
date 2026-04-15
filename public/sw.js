// ─── Cache names ─────────────────────────────────────────────────────────────
// Bump CACHE_VER whenever you want to force-refresh all clients.
const CACHE_VER   = 'nlah-v4';
const SHELL_CACHE = `${CACHE_VER}-shell`;   // HTML pages (checklist shell)
const ASSET_CACHE = `${CACHE_VER}-assets`;  // JS / CSS / fonts / images

// ─── URLs that always bypass the SW ──────────────────────────────────────────
const BYPASS = (url) =>
    url.includes('/livewire/update') ||
    url.includes('/livewire/') ||
    url.includes('/api/maintenance/checklist/sync');

// ─── Checklist origins we want to serve instantly ────────────────────────────
const CHECKLIST_PATHS = [
    '/Maintenance/checklist/check',
    '/Maintenance/checklist/verify',
];

// ─── Install: just activate immediately ──────────────────────────────────────
// We do NOT precache the checklist URL here — it requires authentication, so
// fetch() during install would follow the login redirect and return a non-ok
// response, causing addAll() to throw and the entire SW install to fail
// (which produces ERR_FAILED on the next page load).
//
// The stale-while-revalidate strategy below caches pages naturally on first
// visit instead.  After the first real load the page is available offline.
self.addEventListener('install', () => self.skipWaiting());

// ─── Activate: enable navigation preload + evict old caches ──────────────────
// Navigation preload tells the browser to start the HTTP request for the page
// *while* the service worker is booting.  By the time our fetch handler runs,
// the network response may already be in-flight — effectively hiding SW startup
// latency (~50-150 ms on low-end phones).
self.addEventListener('activate', (e) => {
    e.waitUntil(
        Promise.all([
            // Enable navigation preloading (Chrome/Android only, ignored elsewhere)
            self.registration.navigationPreload?.enable(),

            // Evict every cache whose name doesn't start with our current version.
            caches.keys().then((keys) =>
                Promise.all(
                    keys
                        .filter((k) => !k.startsWith(CACHE_VER))
                        .map((k) => caches.delete(k))
                )
            ),
        ]).then(() => self.clients.claim())
    );
});

// ─── Fetch ────────────────────────────────────────────────────────────────────
self.addEventListener('fetch', (e) => {
    const req = e.request;
    if (req.method !== 'GET') return;
    if (BYPASS(req.url))     return;

    const url = new URL(req.url);

    // 1. Versioned build assets  (/build/assets/…)  → cache-forever, cache-first
    //    These filenames include a content hash so they never go stale.
    if (url.pathname.startsWith('/build/')) {
        e.respondWith(assetCacheFirst(req));
        return;
    }

    // 2. Public storage files (proof photos, etc.) → cache-first with fallback
    if (url.pathname.startsWith('/storage/')) {
        e.respondWith(assetCacheFirst(req));
        return;
    }

    // 3. Checklist HTML pages → stale-while-revalidate
    //    Serve the cached shell IMMEDIATELY (feels instant), then silently
    //    fetch a fresh copy from the network and drop it in cache for next time.
    const isChecklist = CHECKLIST_PATHS.some((p) => url.pathname.startsWith(p));
    const isHTML = req.headers.get('accept')?.includes('text/html');
    if (isHTML && isChecklist) {
        e.respondWith(staleWhileRevalidate(req, e.preloadResponse));
        return;
    }

    // 4. All other HTML (dashboard, HR, etc.) → network-first, fallback to cache
    if (isHTML) {
        e.respondWith(networkFirst(req));
        return;
    }

    // 5. Everything else (fonts, svg, favicon …) → cache-first
    e.respondWith(assetCacheFirst(req));
});

// ─── Strategy helpers ─────────────────────────────────────────────────────────

/**
 * Stale-while-revalidate for HTML shells.
 *
 * Returns the cached response right away (zero wait) while simultaneously
 * making a network request.  When the network responds, it updates the cache
 * so the *next* visit is also fresh.  If there is no cache entry yet and the
 * network is reachable the normal network response is used and then cached.
 *
 * @param {Request}         req
 * @param {Promise|undefined} preloadResponse  Navigation preload promise
 */
async function staleWhileRevalidate(req, preloadResponse) {
    const cache  = await caches.open(SHELL_CACHE);
    const cached = await cache.match(req);

    // Fire the network request (or use preloaded response — whichever is ready)
    const networkFetch = (async () => {
        try {
            // Use the navigation-preload response if it's available.
            const res = (preloadResponse && await preloadResponse) || await fetch(req);
            if (res && res.ok) {
                cache.put(req, res.clone()).catch(() => {});
            }
            return res;
        } catch {
            return null;
        }
    })();

    // If we already have something cached, return it NOW and let the network
    // update run silently in the background.
    if (cached) return cached;

    // No cache entry yet — wait for the network (first visit after install).
    const networkRes = await networkFetch;
    if (networkRes) return networkRes;

    // Fully offline and no cache: minimal offline page.
    return offlinePage();
}

/**
 * Network-first for non-checklist HTML pages.
 * Falls back to cached copy when offline, then minimal offline page.
 */
async function networkFirst(req) {
    const cache = await caches.open(SHELL_CACHE);
    try {
        const res = await fetch(req);
        if (res.ok) cache.put(req, res.clone()).catch(() => {});
        return res;
    } catch {
        const cached = await cache.match(req);
        return cached || offlinePage();
    }
}

/**
 * Cache-first for immutable assets.
 * Stores the response on the first fetch; every subsequent request is instant.
 */
async function assetCacheFirst(req) {
    const cache  = await caches.open(ASSET_CACHE);
    const cached = await cache.match(req);
    if (cached) return cached;
    try {
        const res = await fetch(req);
        if (res.ok) cache.put(req, res.clone()).catch(() => {});
        return res;
    } catch {
        return new Response('', { status: 503 });
    }
}

function offlinePage() {
    return new Response(
        `<!doctype html><html><head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Offline — NLAH</title>
<style>
  body{font-family:system-ui,sans-serif;display:flex;align-items:center;
       justify-content:center;min-height:100vh;margin:0;background:#f3f4f6}
  .box{text-align:center;padding:2rem;max-width:320px}
  .icon{font-size:3rem;margin-bottom:1rem}
  .title{font-size:1.1rem;font-weight:700;color:#1f2937;margin-bottom:.5rem}
  .msg{color:#6b7280;font-size:.875rem;line-height:1.5}
  .btn{margin-top:1.5rem;padding:.6rem 1.4rem;background:#097b86;color:#fff;
       border:none;border-radius:.5rem;font-size:.875rem;cursor:pointer}
</style>
</head><body>
<div class="box">
  <div class="icon">📡</div>
  <div class="title">You're Offline</div>
  <div class="msg">
    Check your connection and try again.<br>
    Proof photos you've taken are saved locally and will sync when you're back online.
  </div>
  <button class="btn" onclick="location.reload()">Retry</button>
</div>
</body></html>`,
        { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } }
    );
}
