<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $festival->title }} - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-[Inter] antialiased">
    @include('partials.navbar')
    <div class="max-w-4xl mx-auto px-6 py-10">
        <a href="{{ route('festivals.index') }}" class="text-orange-600 font-medium hover:underline text-sm mb-6 block"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Calendar</a>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r {{ $festival->is_major ? 'from-red-600 to-orange-500' : 'from-amber-600 to-orange-500' }} px-8 py-10 text-white">
                <span class="text-xs font-bold uppercase tracking-wider text-white/70">{{ ucfirst($festival->category ?? 'Festival') }}</span>
                <h1 class="text-3xl font-[Outfit] font-bold mt-2">{{ $festival->title }}</h1>
                <div class="flex gap-4 mt-3 text-sm text-white/80">
                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ $festival->festival_date->format('d M Y, l') }}</span>
                    @if($festival->tithi)<span><i class="fa-solid fa-moon mr-1"></i>{{ $festival->tithi }}</span>@endif
                </div>
            </div>
            <div class="p-8">
                @if($festival->description)
                    <div class="prose max-w-none text-gray-700 leading-relaxed">{{ $festival->description }}</div>
                @endif
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('pandit.search') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl inline-flex items-center gap-2 transition"><i class="fa-solid fa-hands-praying"></i> Book Pandit for {{ $festival->title }}</a>
                </div>
            </div>
        </div>
        @if($nearby->count() > 0)
            <h3 class="text-lg font-bold text-gray-900 mt-8 mb-4">Nearby Festivals</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($nearby as $f)<a href="{{ route('festivals.show', $f) }}" class="bg-white rounded-xl shadow-sm border p-4 hover:shadow-md transition"><h4 class="font-bold text-sm">{{ $f->title }}</h4><p class="text-xs text-gray-500">{{ $f->festival_date->format('d M Y') }}</p></a>@endforeach
            </div>
        @endif
    </div>
    @include('partials.footer')
</body></html>
