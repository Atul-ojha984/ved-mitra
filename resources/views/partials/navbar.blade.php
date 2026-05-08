@php($brand = config('brand'))
@once
    <style>
        .brand-heading { font-family: 'Cinzel', 'Outfit', serif; letter-spacing: 0; }
        .temple-gradient { background: linear-gradient(135deg, #6b1111 0%, #a3280f 42%, #f97316 72%, #d6a83f 100%); }
        .divine-glow { box-shadow: 0 0 34px rgba(251, 191, 36, 0.28); }
    </style>
@endonce
<nav class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl border-b border-amber-200/60 shadow-sm no-print" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-6">
        <div class="min-h-16 py-3 flex items-center justify-between gap-5">
            <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
                <span class="h-11 w-11 rounded-full temple-gradient divine-glow flex items-center justify-center text-white"><i class="fa-solid fa-om text-lg"></i></span>
                <span>
                    <span class="brand-heading block text-xl font-bold text-[#431407] leading-none">{{ config('app.name', 'Ved Mitra') }}</span>
                    <span class="hidden sm:block text-[11px] uppercase tracking-[0.22em] text-orange-700/75 mt-1">{{ $brand['tagline'] }}</span>
                </span>
            </a>

            <div class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-700">
                <a href="{{ url('/') }}" class="hover:text-orange-700 transition">Home</a>
                <a href="{{ route('pandit.search') }}" class="hover:text-orange-700 transition">Book Pandit</a>
                <a href="{{ route('kundli.form') }}" class="hover:text-orange-700 transition">Kundli</a>
                <a href="{{ route('festivals.index') }}" class="hover:text-orange-700 transition">Festivals</a>
                <a href="{{ route('rashi.index') }}" class="hover:text-orange-700 transition">Horoscope</a>
                <a href="{{ route('ebooks.index') }}" class="hover:text-orange-700 transition">E-books</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-[#431407] px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-[#6b1111] transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-orange-700">Login</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-orange-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-orange-600/20 hover:bg-orange-700 transition">Sign Up</a>
                @endauth
            </div>

            <button type="button" @click="open = !open" class="lg:hidden h-11 w-11 rounded-full border border-amber-200 bg-white text-[#431407]" aria-label="Open mobile menu">
                <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="lg:hidden border-t border-amber-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-4 grid gap-2 text-sm font-semibold text-slate-700">
            <a href="{{ url('/') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">Home</a>
            <a href="{{ route('pandit.search') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">Book Pandit</a>
            <a href="{{ route('kundli.form') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">Kundli</a>
            <a href="{{ route('festivals.index') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">Festivals</a>
            <a href="{{ route('rashi.index') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">Horoscope</a>
            <a href="{{ route('ebooks.index') }}" class="rounded-xl px-3 py-2 hover:bg-orange-50">E-books</a>
            @auth
                <a href="{{ route('dashboard') }}" class="mt-2 rounded-xl bg-[#431407] px-3 py-3 text-center text-white">Dashboard</a>
            @else
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <a href="{{ route('login') }}" class="rounded-xl border border-amber-200 px-3 py-3 text-center">Login</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-orange-600 px-3 py-3 text-center text-white">Sign Up</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
