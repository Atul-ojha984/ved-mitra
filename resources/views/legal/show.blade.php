<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head-assets', ['title' => $page['title'], 'description' => $page['summary']])
</head>
<body class="font-sans text-slate-900 antialiased">
    @include('partials.navbar')
    @include('partials.toasts')

    <header class="relative overflow-hidden temple-gradient text-white">
        <div class="diya-field">
            @foreach(range(1, 10) as $i)
                <span style="left: {{ $i * 9 }}%; animation-delay: -{{ $i }}s;"></span>
            @endforeach
        </div>
        <div class="max-w-5xl mx-auto px-6 py-16 relative z-10">
            <p class="uppercase tracking-[0.25em] text-amber-100 text-xs font-bold mb-3">{{ config('app.name', 'Ved Mitra') }}</p>
            <h1 class="brand-heading text-4xl md:text-5xl font-bold">{{ $page['title'] }}</h1>
            <p class="mt-4 text-orange-50 text-lg leading-relaxed max-w-3xl">{{ $page['summary'] }}</p>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="divine-card rounded-2xl p-6 md:p-9">
            <div class="space-y-8">
                @foreach($page['sections'] as $section)
                    <section>
                        <h2 class="brand-heading text-2xl font-bold text-[#431407] mb-3">{{ $section['heading'] }}</h2>
                        <p class="text-slate-700 leading-8">{{ $section['body'] }}</p>
                    </section>
                @endforeach
            </div>
            <div class="mt-10 rounded-2xl bg-orange-50 border border-orange-100 p-5 text-sm text-orange-900">
                Last updated: {{ now()->format('F d, Y') }}. For support, email <a class="font-bold underline" href="mailto:{{ config('brand.support_email') }}">{{ config('brand.support_email') }}</a>.
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
