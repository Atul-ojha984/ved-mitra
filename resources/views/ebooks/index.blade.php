<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.head-assets', [
        'title' => 'Spiritual E-Book Library',
        'description' => 'Read premium Hindi devotional e-books on Ved Mitra with meanings, chapters, bookmarks, night mode, search, and PDF print support.',
        'image' => config('brand.images.ebooks'),
    ])
</head>
<body class="font-sans text-slate-900 antialiased">
    @include('partials.navbar')
    @include('partials.toasts')

    <header class="relative overflow-hidden temple-gradient text-white">
        <div class="absolute inset-0 opacity-25 bg-cover bg-center" style="background-image: url('{{ config('brand.images.ebooks') }}')"></div>
        <div class="absolute inset-0 bg-[#431407]/70"></div>
        <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
            <p class="uppercase tracking-[0.25em] text-xs font-bold text-amber-100">Sacred Library</p>
            <h1 class="brand-heading text-4xl md:text-6xl font-bold mt-3">Spiritual E-Books</h1>
            <p class="mt-4 max-w-2xl text-orange-50 leading-8">Hindi devotional texts with chapter-wise reading, meanings, bookmarks, night mode, search, and PDF-friendly print layout.</p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <form method="GET" class="divine-card rounded-2xl p-4 md:p-5 mb-8 grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4">
            <div class="relative">
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-orange-700"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Hanuman Chalisa, vrat katha, meanings..." class="w-full rounded-xl border border-amber-200 bg-white py-3 pl-11 pr-4 outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <button class="rounded-xl bg-[#431407] px-6 py-3 font-bold text-white hover:bg-[#6b1111] transition">Search Library</button>
            <input type="hidden" name="category" value="{{ request('category') }}">
        </form>

        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('ebooks.index') }}" class="px-5 py-2 rounded-xl text-sm font-bold {{ !request('category') ? 'bg-orange-600 text-white shadow-lg' : 'bg-white text-slate-600 border border-amber-200' }}">All</a>
            @foreach($categories as $key => $label)
                <a href="{{ route('ebooks.index', ['category' => $key]) }}" class="px-5 py-2 rounded-xl text-sm font-bold {{ request('category') === $key ? 'bg-orange-600 text-white shadow-lg' : 'bg-white text-slate-600 border border-amber-200' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($ebooks as $book)
                @php
                    $cover = $book->cover_image
                        ? (\Illuminate\Support\Str::startsWith($book->cover_image, ['http://', 'https://']) ? $book->cover_image : asset('storage/'.$book->cover_image))
                        : config('brand.images.ebooks');
                @endphp
                <a href="{{ route('ebooks.show', $book) }}" class="group bg-white rounded-2xl shadow-divine border border-amber-100 overflow-hidden hover:-translate-y-1 transition duration-300">
                    <div class="h-64 bg-orange-50 overflow-hidden relative">
                        <img src="{{ $cover }}" alt="{{ $book->title }} cover" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-0.5 rounded-full text-xs font-bold text-slate-700"><i class="fa-solid fa-eye text-[10px] mr-1"></i>{{ $book->views }}</div>
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-bold uppercase text-orange-700 tracking-wider">{{ $categories[$book->category] ?? $book->category }}</span>
                        <h2 class="text-lg font-bold text-[#431407] mt-1 group-hover:text-orange-700 transition line-clamp-2">{{ $book->title }}</h2>
                        <p class="text-slate-500 text-sm mt-2 line-clamp-2">{{ $book->description }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-16 text-center shadow-sm border border-amber-100"><i class="fa-solid fa-book text-5xl text-orange-200 mb-4"></i><h2 class="text-xl font-bold text-slate-900 mb-2">No Books Found</h2><p class="text-slate-500">Try another search or category.</p></div>
            @endforelse
        </div>
        <div class="mt-8">{{ $ebooks->links() }}</div>
    </main>

    @include('partials.footer')
</body>
</html>
