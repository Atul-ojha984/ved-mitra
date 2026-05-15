<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head-assets', [
        'title' => 'AI Kundli Generator',
        'description' => 'Generate a Ved Mitra Vedic kundli with rashi, nakshatra, dosha, lucky colors, puja guidance, and spiritual insights.',
        'image' => config('brand.images.kundli'),
    ])
</head>
<body class="font-sans text-slate-900 antialiased">
    @include('partials.navbar')
    @include('partials.toasts')

    <header class="relative overflow-hidden temple-gradient text-white">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="diya-field">
            @foreach(range(1, 12) as $i)
                <span style="left: {{ $i * 8 }}%; animation-delay: -{{ $i * 0.8 }}s;"></span>
            @endforeach
        </div>
        <div class="max-w-6xl mx-auto px-6 py-16 relative z-10">
            <p class="uppercase tracking-[0.28em] text-amber-100 text-xs font-bold mb-3">{{ config('brand.tagline') }}</p>
            <h1 class="brand-heading text-4xl md:text-6xl font-bold mb-4">AI Kundli Generator</h1>
            <p class="text-orange-50 text-lg max-w-2xl">Enter accurate birth details to generate a Vedic insight report with rashi, nakshatra, dosha analysis, remedies, and suggested pujas.</p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <section class="lg:col-span-2 divine-card rounded-2xl p-6 md:p-8">
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm border border-red-100">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('kundli.generate') }}" class="space-y-5" data-kundli-form data-secure-form novalidate>
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required autocomplete="name" placeholder="Enter your full name" class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" required max="{{ now()->subDay()->toDateString() }}" class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Time of Birth</label>
                            <input type="time" name="birth_time" value="{{ old('birth_time') }}" required class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Birth Place</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" required autocomplete="address-level2" placeholder="e.g. Varanasi, Uttar Pradesh" class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <button type="submit" data-submit-button class="w-full rounded-xl temple-gradient px-6 py-4 text-lg font-bold text-white shadow-glow transition hover:brightness-105 disabled:cursor-wait disabled:opacity-80 flex items-center justify-center gap-3">
                        <span data-spinner class="hidden h-5 w-5 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                        <i data-submit-icon class="fa-solid fa-scroll"></i>
                        <span data-submit-text>Generate Kundli</span>
                    </button>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="divine-card rounded-2xl p-6">
                    <h2 class="brand-heading text-lg font-bold text-[#431407] mb-4"><i class="fa-solid fa-clock-rotate-left text-orange-600 mr-2"></i>Recent Kundlis</h2>
                    @forelse($history as $k)
                        <a href="{{ route('kundli.show', $k) }}" class="block py-3 border-b border-amber-100 last:border-0 hover:bg-orange-50 -mx-2 px-2 rounded-lg transition">
                            <p class="font-bold text-slate-900 text-sm">{{ $k->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $k->dob->format('d M Y') }} · {{ $k->birth_place }}</p>
                        </a>
                    @empty
                        <p class="text-slate-400 text-sm text-center py-6">No kundlis generated yet</p>
                    @endforelse
                </div>
                <div class="rounded-2xl overflow-hidden shadow-divine">
                    <img src="{{ config('brand.images.kundli') }}" alt="Vedic kundli reading" loading="lazy" class="h-72 w-full object-cover">
                </div>
            </aside>
        </div>
    </main>

    @include('partials.footer')
    @include('partials.form-security')
    <script>
        document.querySelectorAll('[data-kundli-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                if (window.VedForms && !window.VedForms.validateForm(form)) return;
                const button = form.querySelector('[data-submit-button]');
                form.querySelector('[data-spinner]')?.classList.remove('hidden');
                form.querySelector('[data-submit-icon]')?.classList.add('hidden');
                form.querySelector('[data-submit-text]').textContent = 'Generating Kundli...';
                button.disabled = true;
                window.vedToast?.('Kundli generation started. Please wait.', 'info');
            });
        });
    </script>
</body>
</html>
