<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Ved Mitra') }} Pandit Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('head')
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Inter','sans-serif'], heading: ['Outfit','sans-serif'] },
            colors: { brand: { 50:'#fff7ed',100:'#ffedd5',500:'#f97316',600:'#ea580c',900:'#7c2d12' } }
        }}}
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans flex h-screen overflow-hidden">

    @php
        $profile = auth()->user()->panditProfile;
        $pendingBookingCount = $profile ? $profile->bookings()->where('status','pending')->count() : 0;
    @endphp

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col hidden md:flex h-full shadow-2xl shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <a href="{{ route('pandit.dashboard') }}" class="text-xl font-heading font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-om text-brand-500"></i> {{ config('app.name', 'Ved Mitra') }} Pandit
            </a>
        </div>
        <div class="p-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 rounded-full border-2 border-brand-500">
                <div class="min-w-0"><p class="font-medium text-white text-sm truncate">{{ auth()->user()->name }}</p><p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p></div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-3">
                @php $r = fn($name) => request()->routeIs($name) ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-white/10 hover:text-white'; @endphp
                <li><a href="{{ route('pandit.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.dashboard') }} transition text-sm"><i class="fa-solid fa-chart-pie w-5"></i> Overview</a></li>
                <li><a href="{{ route('pandit.bookings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.bookings') }} transition text-sm"><i class="fa-solid fa-calendar-check w-5"></i> Bookings @if($pendingBookingCount > 0)<span class="bg-red-500 text-xs py-0.5 px-2 rounded-full ml-auto animate-pulse">{{ $pendingBookingCount }}</span>@endif</a></li>
                <li><a href="{{ route('pandit.availability') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.availability') }} transition text-sm"><i class="fa-solid fa-clock w-5"></i> Availability</a></li>
                <li><a href="{{ route('pandit.calendar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.calendar') }} transition text-sm"><i class="fa-regular fa-calendar-days w-5"></i> Calendar</a></li>
                <li><a href="{{ route('pandit.earnings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.earnings') }} transition text-sm"><i class="fa-solid fa-indian-rupee-sign w-5"></i> Earnings</a></li>
                <li><a href="{{ route('pandit.reviews') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $r('pandit.reviews') }} transition text-sm"><i class="fa-solid fa-star w-5"></i> Reviews</a></li>
            </ul>
        </nav>
        <div class="p-4 border-t border-white/10 space-y-1">
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white transition text-sm"><i class="fa-solid fa-globe w-5"></i> View Site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400 hover:bg-red-500/20 transition text-sm w-full text-left"><i class="fa-solid fa-right-from-bracket w-5"></i> Logout</button></form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10 border-b border-gray-100 shrink-0">
            <h2 class="text-lg font-heading font-bold text-gray-800">@yield('page_title', 'Dashboard')</h2>
            <div class="flex items-center gap-3">
                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold"><i class="fa-solid fa-circle text-[6px] mr-1"></i> Approved</span>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50/50">
            @if(session('success'))<div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm border border-green-100 flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>@endif
            @yield('content')
        </div>
    </main>
    @include('partials.form-security')
</body>
</html>
