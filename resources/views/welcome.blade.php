<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head-assets', [
        'description' => 'Ved Mitra is your divine spiritual companion for verified pandit booking, Vedic kundli generation, Hindu festival wisdom, and Hindi devotional e-books.',
        'image' => config('brand.images.hero'),
    ])
    <style>
        .home-hero {
            min-height: 84vh;
            background:
                linear-gradient(90deg, rgba(42, 13, 9, 0.88), rgba(67, 20, 7, 0.58) 46%, rgba(42, 13, 9, 0.18)),
                url('{{ config('brand.images.hero') }}') center/cover no-repeat;
        }
    </style>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebSite",
            "name": "{{ config('app.name', 'Ved Mitra') }}",
            "description": "{{ config('brand.description') }}",
            "url": "{{ url('/') }}",
            "potentialAction": {
                "@@type": "SearchAction",
                "target": "{{ route('pandit.search') }}?service={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        }
    </script>
</head>
<body class="font-sans text-slate-900 antialiased">
    @include('partials.toasts')

    <header class="home-hero relative flex flex-col overflow-hidden text-white">
        <div class="diya-field">
            @foreach(range(1, 18) as $i)
                <span style="left: {{ $i * 5 }}%; animation-delay: -{{ $i * 0.7 }}s; animation-duration: {{ 10 + ($i % 5) }}s;"></span>
            @endforeach
        </div>
        <nav class="relative z-20 py-6 no-print">
            <div class="max-w-7xl mx-auto px-6 flex items-center justify-between gap-5">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="h-12 w-12 rounded-full bg-white/15 border border-amber-200/40 backdrop-blur flex items-center justify-center divine-glow"><i class="fa-solid fa-om text-2xl text-amber-300"></i></span>
                    <span>
                        <span class="brand-heading block text-2xl font-bold">{{ config('app.name', 'Ved Mitra') }}</span>
                        <span class="hidden sm:block text-[11px] uppercase tracking-[0.24em] text-amber-100">{{ config('brand.tagline') }}</span>
                    </span>
                </a>
                <div class="hidden lg:flex items-center gap-7 text-sm font-semibold text-white/86">
                    <a href="{{ route('pandit.search') }}" class="hover:text-amber-200">Book Pandit</a>
                    <a href="{{ route('kundli.form') }}" class="hover:text-amber-200">Kundli</a>
                    <a href="{{ route('festivals.index') }}" class="hover:text-amber-200">Festivals</a>
                    <a href="{{ route('ebooks.index') }}" class="hover:text-amber-200">E-books</a>
                    <a href="#contact" class="hover:text-amber-200">Contact</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-bold text-[#431407] shadow-lg hover:bg-amber-50 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline text-sm font-bold text-white hover:text-amber-200">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-5 py-2.5 text-sm font-bold text-[#431407] shadow-lg shadow-amber-400/20 hover:bg-amber-300 transition">Sign Up</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="relative z-10 flex-1 flex items-center">
            <div class="max-w-7xl mx-auto px-6 w-full py-16">
                <div class="max-w-3xl">
                    <p class="inline-flex items-center gap-2 rounded-full border border-amber-200/40 bg-white/10 px-4 py-2 text-sm font-bold text-amber-100 backdrop-blur">
                        <i class="fa-solid fa-sparkles"></i> {{ config('brand.name_caps') }}
                    </p>
                    <h1 class="brand-heading mt-6 text-5xl md:text-7xl font-bold leading-tight">Your Divine Spiritual Companion</h1>
                    <p class="mt-6 max-w-2xl text-lg md:text-xl text-orange-50 leading-8">Book verified pandits, generate kundli insights, follow Hindu festivals, and read sacred Hindi texts in a calm, premium spiritual ecosystem.</p>
                    <form action="{{ route('pandit.search') }}" method="GET" class="mt-9 grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-3 max-w-4xl">
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-orange-700"></i>
                            <input type="text" name="location" placeholder="City or Pincode" class="w-full rounded-xl border-0 bg-white/95 py-4 pl-11 pr-4 text-slate-900 outline-none focus:ring-2 focus:ring-amber-300">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-om absolute left-4 top-1/2 -translate-y-1/2 text-orange-700"></i>
                            <select name="service" class="w-full rounded-xl border-0 bg-white/95 py-4 pl-11 pr-4 text-slate-900 outline-none focus:ring-2 focus:ring-amber-300">
                                <option value="">Select Service / Puja</option>
                                <option value="Griha Pravesh">Griha Pravesh</option>
                                <option value="Satyanarayan Katha">Satyanarayan Katha</option>
                                <option value="Marriage Ceremony">Marriage Ceremony</option>
                                <option value="Ganesh Puja">Ganesh Puja</option>
                                <option value="Rudrabhishek">Rudrabhishek</option>
                            </select>
                        </div>
                        <button type="submit" class="rounded-xl bg-amber-400 px-7 py-4 font-bold text-[#431407] shadow-glow hover:bg-amber-300 transition flex items-center justify-center gap-2"><i class="fa-solid fa-search"></i>Find Pandit</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="bg-[#fff8eb] py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-9">
                    <div>
                        <p class="uppercase tracking-[0.25em] text-xs font-bold text-orange-700">Divine Services</p>
                        <h2 class="brand-heading text-3xl md:text-5xl font-bold text-[#431407] mt-2">Rituals For Every Sacred Moment</h2>
                    </div>
                    <a href="{{ route('pandit.search') }}" class="inline-flex items-center gap-2 font-bold text-orange-800 hover:text-[#431407]">Explore services <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['title' => 'Griha Pravesh Puja', 'meta' => '3-4 Hours', 'price' => '₹3,100', 'image' => config('brand.images.puja'), 'service' => 'Griha Pravesh', 'desc' => 'Invite auspicious energy into your new home with authentic Vedic rituals.'],
                        ['title' => 'Yagya & Havan', 'meta' => 'Personalized Vidhi', 'price' => '₹5,100', 'image' => config('brand.images.yagya'), 'service' => 'Navgrah Shanti', 'desc' => 'Sacred fire rituals for graha shanti, prosperity, and family well-being.'],
                        ['title' => 'Durga & Festival Puja', 'meta' => 'Festival Ready', 'price' => '₹2,100', 'image' => config('brand.images.durga'), 'service' => 'Durga Puja', 'desc' => 'Celebrate vrat, utsav, and family puja with verified pandit guidance.'],
                    ] as $service)
                        <article class="group overflow-hidden rounded-2xl bg-white shadow-divine border border-amber-100">
                            <img src="{{ $service['image'] }}" alt="{{ $service['title'] }}" loading="lazy" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="p-6">
                                <div class="flex justify-between gap-4 mb-3">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-950">{{ $service['title'] }}</h3>
                                        <p class="text-sm text-slate-500"><i class="fa-regular fa-clock mr-1"></i>{{ $service['meta'] }}</p>
                                    </div>
                                    <span class="h-fit rounded-lg bg-orange-50 px-3 py-1 text-sm font-bold text-orange-700">{{ $service['price'] }}</span>
                                </div>
                                <p class="text-slate-600 leading-7">{{ $service['desc'] }}</p>
                                <a href="{{ route('pandit.search', ['service' => $service['service']]) }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#431407] px-4 py-3 font-bold text-white hover:bg-[#6b1111] transition">Book Now <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="overflow-hidden rounded-2xl shadow-divine">
                    <img src="{{ config('brand.images.kundli') }}" alt="Kundli reading" loading="lazy" class="h-[32rem] w-full object-cover">
                </div>
                <div>
                    <p class="uppercase tracking-[0.25em] text-xs font-bold text-orange-700">Kundli</p>
                    <h2 class="brand-heading text-3xl md:text-5xl font-bold text-[#431407] mt-2">Generate Janam Kundli With Remedies</h2>
                    <p class="mt-5 text-slate-600 leading-8">Get rashi, nakshatra, lagna, dosha analysis, lucky colors, lucky numbers, and suggested pujas. The generation flow now validates inputs, shows loading feedback, and opens a polished results page.</p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('kundli.form') }}" class="inline-flex items-center gap-2 rounded-xl temple-gradient px-6 py-4 font-bold text-white shadow-glow hover:brightness-105 transition"><i class="fa-solid fa-scroll"></i>Generate Kundli</a>
                        <a href="{{ route('rashi.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-orange-50 px-6 py-4 font-bold text-[#431407] hover:bg-orange-100 transition"><i class="fa-solid fa-sun"></i>Daily Horoscope</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-[#fff8eb]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-[0.85fr_1.15fr] gap-10 items-start">
                    <div>
                        <p class="uppercase tracking-[0.25em] text-xs font-bold text-orange-700">Sacred Library</p>
                        <h2 class="brand-heading text-3xl md:text-5xl font-bold text-[#431407] mt-2">Hindi E-books With Meanings</h2>
                        <p class="mt-5 text-slate-600 leading-8">Read Hanuman Chalisa, Durga Chalisa, Shiv Chalisa, vrat kathas, and Ramayan Saar with chapter navigation, meanings, bookmarks, search, night mode, and PDF print support.</p>
                        <a href="{{ route('ebooks.index') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-[#431407] px-6 py-4 font-bold text-white hover:bg-[#6b1111] transition"><i class="fa-solid fa-book-open"></i>Open Library</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        @foreach([
                            ['title' => 'Hanuman Chalisa', 'image' => 'https://vediccosmos.com/wp-content/uploads/2023/04/HC-book-with-bookmarker.jpg'],
                            ['title' => 'Durga Chalisa', 'image' => 'https://tse2.mm.bing.net/th/id/OIP.hK1_Nbclv4YM0HUHdHMGvwHaLR?rs=1&pid=ImgDetMain&o=7&rm=3'],
                            ['title' => 'Ramayan Saar', 'image' => 'https://m.media-amazon.com/images/I/71G3QlxdjgL._SL1500_.jpg'],
                        ] as $book)
                            <a href="{{ route('ebooks.index', ['search' => $book['title']]) }}" class="group overflow-hidden rounded-2xl bg-white shadow-divine border border-amber-100">
                                <img src="{{ $book['image'] }}" alt="{{ $book['title'] }}" loading="lazy" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                                <div class="p-4">
                                    <h3 class="font-bold text-[#431407]">{{ $book['title'] }}</h3>
                                    <p class="text-sm text-slate-500">Read with Hindi meaning</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <p class="uppercase tracking-[0.25em] text-xs font-bold text-orange-700">Festivals</p>
                    <h2 class="brand-heading text-3xl md:text-5xl font-bold text-[#431407] mt-2">Stay Close To Every Tithi</h2>
                    <p class="mt-5 text-slate-600 leading-8">Follow major Hindu festivals, vrat dates, auspicious timings, and devotional context so your family ceremonies feel prepared and peaceful.</p>
                    <a href="{{ route('festivals.index') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-orange-600 px-6 py-4 font-bold text-white hover:bg-orange-700 transition"><i class="fa-solid fa-calendar-days"></i>Festival Calendar</a>
                </div>
                <div class="overflow-hidden rounded-2xl shadow-divine">
                    <img src="{{ config('brand.images.festival') }}" alt="Hindu festival diyas and aarti" loading="lazy" class="h-[30rem] w-full object-cover">
                </div>
            </div>
        </section>

        <section class="py-16 bg-[#fff8eb]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-10">
                    <p class="uppercase tracking-[0.25em] text-xs font-bold text-orange-700">Verified Guidance</p>
                    <h2 class="brand-heading text-3xl md:text-5xl font-bold text-[#431407] mt-2">Pandits For Pujas, Kathas, And Consultations</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['name' => 'Acharya for Home Puja', 'skill' => 'Griha Pravesh, Ganesh Puja, Vastu Shanti', 'image' => config('brand.images.pandit')],
                        ['name' => 'Vedic Havan Specialist', 'skill' => 'Navgrah Shanti, Rudrabhishek, Havan', 'image' => config('brand.images.yagya')],
                        ['name' => 'Festival Katha Pandit', 'skill' => 'Satyanarayan Katha, Vrat Vidhi, Aarti', 'image' => config('brand.images.temple')],
                    ] as $pandit)
                        <article class="rounded-2xl bg-white shadow-divine overflow-hidden border border-amber-100">
                            <img src="{{ $pandit['image'] }}" alt="{{ $pandit['name'] }}" loading="lazy" class="h-56 w-full object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-slate-950">{{ $pandit['name'] }}</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ $pandit['skill'] }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-sm font-bold text-amber-700"><i class="fa-solid fa-star"></i> 4.9 trusted</span>
                                    <a href="{{ route('pandit.search') }}" class="text-sm font-bold text-orange-700 hover:text-[#431407]">View <i class="fa-solid fa-arrow-right text-xs"></i></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    @include('partials.mantra-player')
    @include('partials.footer')
</body>
</html>
