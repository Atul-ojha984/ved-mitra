@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
])
@php
    $brand = config('brand');
    $siteName = config('app.name', 'Ved Mitra');
    $pageTitle = $title ? $title.' - '.$siteName : $siteName.' - '.$brand['tagline'];
    $pageDescription = $description ?: $brand['description'];
    $pageImage = $image ?: $brand['images']['hero'];
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $brand['keywords'] }}">
<meta name="author" content="{{ $siteName }}">
<meta name="theme-color" content="#9a3412">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">
<link rel="preconnect" href="https://fonts.bunny.net">
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link href="https://fonts.bunny.net/css?family=cinzel:500,600,700|gotu:400|inter:400,500,600,700,800|noto-serif-devanagari:400,600,700|outfit:400,500,600,700,800" rel="stylesheet" />
<script>
    window.VED_MITRA = @json(['name' => $siteName, 'tagline' => $brand['tagline']]);
    tailwind = window.tailwind || {};
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    heading: ['Cinzel', 'Outfit', 'serif'],
                    display: ['Gotu', 'Noto Serif Devanagari', 'serif'],
                    hindi: ['Noto Serif Devanagari', 'serif'],
                },
                colors: {
                    brand: {
                        50: '#fff8eb',
                        100: '#ffe7bd',
                        200: '#ffd17e',
                        300: '#fbbf24',
                        500: '#f97316',
                        600: '#dc4a08',
                        700: '#b91c1c',
                        800: '#7f1d1d',
                        900: '#431407',
                    },
                    temple: {
                        saffron: '#f97316',
                        gold: '#d6a83f',
                        maroon: '#6b1111',
                        sandal: '#fff4dc',
                    }
                },
                boxShadow: {
                    divine: '0 22px 80px rgba(127, 29, 29, 0.16)',
                    glow: '0 0 36px rgba(251, 191, 36, 0.38)',
                }
            }
        }
    };
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --ved-maroon: #6b1111;
        --ved-saffron: #f97316;
        --ved-gold: #d6a83f;
        --ved-sandal: #fff4dc;
    }
    html {
        scroll-behavior: smooth;
    }
    body {
        background:
            radial-gradient(circle at 12% 8%, rgba(251, 191, 36, 0.16), transparent 28rem),
            linear-gradient(180deg, #fff9ed 0%, #fffaf2 38%, #f8fafc 100%);
    }
    .brand-heading {
        font-family: 'Cinzel', 'Outfit', serif;
        letter-spacing: 0;
    }
    .hindi-copy {
        font-family: 'Noto Serif Devanagari', serif;
    }
    .temple-gradient {
        background: linear-gradient(135deg, #6b1111 0%, #a3280f 42%, #f97316 72%, #d6a83f 100%);
    }
    .divine-card {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(214, 168, 63, 0.2);
        box-shadow: 0 18px 60px rgba(107, 17, 17, 0.09);
    }
    .divine-glow {
        box-shadow: 0 0 0 1px rgba(251, 191, 36, 0.24), 0 0 34px rgba(251, 191, 36, 0.28);
    }
    .diya-field {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }
    .diya-field span {
        position: absolute;
        bottom: -2rem;
        width: 0.45rem;
        height: 0.45rem;
        border-radius: 999px;
        background: rgba(255, 210, 111, 0.78);
        animation: float-diya 12s linear infinite;
        box-shadow: 0 0 18px rgba(251, 191, 36, 0.72);
    }
    @keyframes float-diya {
        from { transform: translate3d(0, 0, 0) scale(0.8); opacity: 0; }
        12% { opacity: 0.8; }
        to { transform: translate3d(1.5rem, -105vh, 0) scale(1.35); opacity: 0; }
    }
    .toast-host {
        position: fixed;
        right: 1rem;
        top: 5rem;
        z-index: 80;
        display: grid;
        gap: 0.75rem;
    }
    .toast-item {
        min-width: min(22rem, calc(100vw - 2rem));
        border-radius: 0.9rem;
        padding: 0.9rem 1rem;
        color: #fff;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
        animation: toast-in 0.22s ease-out;
    }
    .toast-success { background: #15803d; }
    .toast-error { background: #b91c1c; }
    .toast-info { background: #9a3412; }
    @keyframes toast-in {
        from { transform: translateY(-0.5rem); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; }
        .print-surface { box-shadow: none !important; border: 0 !important; }
    }
</style>
<script>
    window.vedToast = function (message, type = 'info') {
        let host = document.querySelector('[data-toast-host]');
        if (!host) {
            host = document.createElement('div');
            host.className = 'toast-host';
            host.setAttribute('data-toast-host', '');
            document.body.appendChild(host);
        }
        const item = document.createElement('div');
        item.className = `toast-item toast-${type}`;
        item.textContent = message;
        host.appendChild(item);
        setTimeout(() => item.remove(), 4200);
    };
</script>
