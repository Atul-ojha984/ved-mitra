<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Outfit', 'sans-serif'] },
            colors: { brand: { 50:'#fff7ed', 100:'#ffedd5', 500:'#f97316', 600:'#ea580c', 900:'#7c2d12' } }
        }}}
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans flex h-screen overflow-hidden">

    @php $pendingCount = \App\Models\PanditProfile::where('approval_status','pending')->count(); @endphp

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex h-full shadow-2xl shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-heading font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-om text-brand-500"></i> {{ config('app.name', 'Ved Mitra') }} Admin
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <p class="px-6 text-xs text-gray-500 uppercase tracking-wider mb-3">Main</p>
            <ul class="space-y-1 px-3">
                <li><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition text-sm">
                    <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
                </a></li>
            </ul>
            <p class="px-6 text-xs text-gray-500 uppercase tracking-wider mt-6 mb-3">Management</p>
            <ul class="space-y-1 px-3">
                <li><a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition text-sm">
                    <i class="fa-solid fa-users w-5"></i> Users
                </a></li>
                <li><a href="{{ route('admin.pandits') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.pandits*') ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition text-sm">
                    <i class="fa-solid fa-user-tie w-5"></i> Pandits
                    @if($pendingCount > 0)<span class="bg-red-500 text-xs py-0.5 px-2 rounded-full ml-auto animate-pulse">{{ $pendingCount }}</span>@endif
                </a></li>
                <li><a href="{{ route('admin.bookings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.bookings') ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition text-sm">
                    <i class="fa-solid fa-calendar-check w-5"></i> Bookings
                </a></li>
                <li><a href="{{ route('admin.payments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.payments') ? 'bg-brand-600 text-white font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition text-sm">
                    <i class="fa-solid fa-indian-rupee-sign w-5"></i> Payments
                </a></li>
            </ul>
        </nav>
        <div class="p-4 border-t border-gray-800 space-y-1">
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white transition text-sm"><i class="fa-solid fa-globe w-5"></i> View Site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400 hover:bg-red-500/20 transition text-sm w-full text-left"><i class="fa-solid fa-right-from-bracket w-5"></i> Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10 border-b border-gray-100 shrink-0">
            <h2 class="text-lg font-heading font-bold text-gray-800">@yield('page_title', 'Admin')</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500 hidden md:block">{{ auth()->user()->name }}</span>
                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-brand-500 to-yellow-400 p-0.5">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-full w-full h-full border-2 border-white object-cover" alt="Admin">
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50/50">
            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm border border-green-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</body>
</html>
