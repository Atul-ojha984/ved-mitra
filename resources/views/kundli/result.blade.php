<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head-assets', [
        'title' => 'Kundli Result - '.$validated['full_name'],
        'description' => 'Ved Mitra kundli result with rashi, nakshatra, lagna, dosha, lucky numbers, and spiritual puja remedies.',
        'image' => config('brand.images.kundli'),
    ])
</head>
<body class="font-sans text-slate-900 antialiased">
    @include('partials.navbar')
    @include('partials.toasts')

    <header class="relative overflow-hidden temple-gradient text-white no-print">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-6xl mx-auto px-6 py-12 relative z-10">
            <p class="uppercase tracking-[0.25em] text-amber-100 text-xs font-bold mb-3">{{ config('app.name', 'Ved Mitra') }} Kundli</p>
            <h1 class="brand-heading text-3xl md:text-5xl font-bold mb-2">{{ $validated['full_name'] }}</h1>
            <p class="text-orange-50">Born: {{ \Carbon\Carbon::parse($validated['dob'])->format('d M Y') }} at {{ $validated['birth_time'] }} · {{ $validated['birth_place'] }}</p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-10 print-surface">
        <div class="flex flex-col md:flex-row gap-3 justify-between md:items-center mb-8 no-print">
            <a href="{{ route('kundli.form') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm font-bold text-[#431407] hover:bg-orange-50 transition"><i class="fa-solid fa-rotate"></i>Generate Another</a>
            <div class="flex flex-wrap gap-3">
                @if($kundli)
                    <a href="{{ route('kundli.pdf', $kundli) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#431407] px-4 py-3 text-sm font-bold text-white hover:bg-[#6b1111] transition"><i class="fa-solid fa-file-pdf"></i>Download PDF</a>
                @endif
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-3 text-sm font-bold text-white hover:bg-orange-700 transition"><i class="fa-solid fa-print"></i>Print / Save PDF</button>
            </div>
        </div>

        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="divine-card rounded-2xl p-6 text-center"><p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Sun Sign</p><h2 class="text-2xl font-bold text-orange-700">{{ $kundliData['rashi']['name'] }}</h2><p class="hindi-copy text-slate-500">{{ $kundliData['rashi']['hindi'] }}</p><p class="text-xs text-slate-400 mt-1">Lord: {{ $kundliData['rashi']['lord'] }}</p></div>
            <div class="divine-card rounded-2xl p-6 text-center"><p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Moon Sign</p><h2 class="text-2xl font-bold text-blue-700">{{ $kundliData['moon_rashi']['name'] }}</h2><p class="hindi-copy text-slate-500">{{ $kundliData['moon_rashi']['hindi'] }}</p><p class="text-xs text-slate-400 mt-1">Element: {{ $kundliData['moon_rashi']['element'] }}</p></div>
            <div class="divine-card rounded-2xl p-6 text-center"><p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Nakshatra</p><h2 class="text-2xl font-bold text-[#6b1111]">{{ $kundliData['nakshatra'] }}</h2></div>
            <div class="divine-card rounded-2xl p-6 text-center"><p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Ascendant</p><h2 class="text-2xl font-bold text-emerald-700">{{ $kundliData['ascendant']['name'] }}</h2><p class="hindi-copy text-slate-500">{{ $kundliData['ascendant']['hindi'] }}</p></div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <section class="lg:col-span-2 space-y-6">
                <div class="divine-card rounded-2xl p-6">
                    <h2 class="brand-heading text-xl font-bold text-[#431407] mb-3"><i class="fa-solid fa-star text-amber-500 mr-2"></i>Horoscope Summary</h2>
                    <p class="text-slate-700 leading-8">{{ $kundliData['horoscope_summary'] }}</p>
                </div>

                <div class="divine-card rounded-2xl p-6">
                    <h2 class="brand-heading text-xl font-bold text-[#431407] mb-3"><i class="fa-solid fa-triangle-exclamation text-red-500 mr-2"></i>Dosha Analysis</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kundliData['doshas'] as $d)
                            <span class="px-3 py-1.5 rounded-xl text-sm font-bold {{ $d === 'Mangal Dosha' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800' }}">{{ $d }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="divine-card rounded-2xl p-6"><h2 class="brand-heading text-lg font-bold text-[#431407] mb-3"><i class="fa-solid fa-briefcase text-blue-600 mr-2"></i>Career</h2><p class="text-slate-700 text-sm leading-7">{{ $kundliData['career'] }}</p></div>
                    <div class="divine-card rounded-2xl p-6"><h2 class="brand-heading text-lg font-bold text-[#431407] mb-3"><i class="fa-solid fa-heart-pulse text-pink-600 mr-2"></i>Health</h2><p class="text-slate-700 text-sm leading-7">{{ $kundliData['health'] }}</p></div>
                </div>

                <div class="divine-card rounded-2xl p-6 no-print">
                    <h2 class="brand-heading text-xl font-bold text-[#431407] mb-3"><i class="fa-solid fa-hands-praying text-orange-600 mr-2"></i>Suggested Pujas</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kundliData['suggested_pujas'] as $puja)
                            <a href="{{ route('pandit.search', ['service' => $puja]) }}" class="px-4 py-2 rounded-xl bg-orange-50 text-orange-800 font-bold text-sm hover:bg-orange-100 transition">{{ $puja }} <i class="fa-solid fa-arrow-right text-xs ml-1"></i></a>
                        @endforeach
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-2xl temple-gradient p-6 text-white shadow-glow">
                    <h2 class="brand-heading font-bold text-xl mb-4">Lucky Numbers</h2>
                    <div class="flex gap-3 mb-6">
                        @foreach($kundliData['lucky_numbers'] as $n)
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center font-bold text-xl">{{ $n }}</div>
                        @endforeach
                    </div>
                    <h2 class="brand-heading font-bold text-xl mb-3">Lucky Colors</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kundliData['lucky_colors'] as $c)
                            <span class="px-3 py-1 rounded-lg bg-white/20 text-sm font-bold">{{ $c }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="divine-card rounded-2xl p-6 no-print">
                    <h2 class="brand-heading font-bold text-[#431407] mb-3">Saved Kundlis</h2>
                    @forelse($history as $k)
                        <a href="{{ route('kundli.show', $k) }}" class="block py-2 border-b border-amber-100 last:border-0 text-sm hover:text-orange-700">
                            {{ $k->full_name }} <span class="text-slate-400">· {{ $k->dob->format('d M') }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-400">Login to save kundli history.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </main>

    @include('partials.footer')
    <script>window.vedToast?.('Kundli generated successfully.', 'success');</script>
</body>
</html>
