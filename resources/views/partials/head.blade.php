<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />

{{-- PWA / home-screen meta --}}
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="NLAH Check">
<meta name="theme-color" content="#015581">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/manifest.json">

{{--
    Register the service worker as early as possible (not on 'load') so it
    can intercept subsequent navigations and cache assets sooner.
--}}
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function (reg) {
            // When a new SW activates (e.g. after a cache-version bump), reload the
            // page once so the user always runs the latest HTML + script code.
            // Without this, iOS Safari keeps serving the old cached page until
            // all tabs are closed manually.
            var refreshing = false;
            navigator.serviceWorker.addEventListener('controllerchange', function () {
                if (!refreshing) {
                    refreshing = true;
                    window.location.reload();
                }
            });
        }).catch(function () {});
    }
</script>

{{--
    Fonts — dns-prefetch + preconnect reduce the DNS/TLS handshake cost.
    font-display=swap in the CSS means text renders with a system font while
    the webfont loads, so the page isn't blank on slow connections.
--}}
<link rel="dns-prefetch" href="https://fonts.bunny.net">
<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

{{-- chart.js is heavy (~200 KB) — only load it on pages that actually need it --}}
@stack('chart-scripts')

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance