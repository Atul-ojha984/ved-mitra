<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hindu Festival Calendar - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-[Inter] antialiased">
    @include('partials.navbar')

    <div class="bg-gradient-to-r from-red-700 to-orange-600 py-16 text-center text-white">
        <h1 class="text-4xl font-[Outfit] font-bold mb-2"><i class="fa-solid fa-calendar-days mr-2"></i> Hindu Festival Calendar</h1>
        <p class="text-orange-100">Tithi, Panchang, Festivals & Auspicious Dates</p>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Upcoming -->
            <div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-20">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4"><i class="fa-solid fa-fire text-red-500 mr-2"></i>Upcoming Festivals</h3>
                    @forelse($upcoming as $f)
                        <a href="{{ route('festivals.show', $f) }}" class="flex items-center gap-3 py-3 {{ !$loop->last?'border-b border-gray-100':'' }} hover:bg-gray-50 -mx-2 px-2 rounded-lg transition">
                            <div class="w-12 h-12 rounded-xl {{ $f->is_major?'bg-red-100 text-red-600':'bg-orange-100 text-orange-600' }} flex items-center justify-center text-center shrink-0"><span class="text-sm font-bold leading-none">{{ $f->festival_date->format('d') }}<br><span class="text-[10px]">{{ $f->festival_date->format('M') }}</span></span></div>
                            <div><p class="font-medium text-gray-900 text-sm">{{ $f->title }}</p><p class="text-xs text-gray-500">{{ $f->tithi ?? $f->category }}</p></div>
                            @if($f->is_major)<i class="fa-solid fa-star text-yellow-400 text-xs ml-auto"></i>@endif
                        </a>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-6">No upcoming festivals</p>
                    @endforelse
                </div>
            </div>

            <!-- All Festivals -->
            <div class="lg:col-span-2">
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('festivals.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ !request('category') ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border' }}">All</a>
                    @foreach(['festival'=>'Festivals','vrat'=>'Vrat','eclipse'=>'Grahan','tithi'=>'Tithi'] as $k=>$v)
                        <a href="{{ route('festivals.index',['category'=>$k]) }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ request('category')===$k?'bg-red-500 text-white':'bg-white text-gray-600 border' }}">{{ $v }}</a>
                    @endforeach
                </div>
                <div class="space-y-4">
                    @forelse($festivals as $f)
                        <a href="{{ route('festivals.show', $f) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl {{ $f->is_major?'bg-gradient-to-br from-red-500 to-orange-500 text-white':'bg-orange-50 text-orange-600' }} flex items-center justify-center text-center shrink-0">
                                    <div><span class="text-lg font-bold leading-none block">{{ $f->festival_date->format('d') }}</span><span class="text-xs">{{ $f->festival_date->format('M Y') }}</span></div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 group-hover:text-orange-600 transition">{{ $f->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $f->tithi ? 'Tithi: '.$f->tithi.' · ' : '' }}{{ ucfirst($f->category ?? '') }}</p>
                                    @if($f->description)<p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $f->description }}</p>@endif
                                </div>
                                @if($f->is_major)<span class="px-2 py-0.5 bg-red-100 text-red-600 text-xs font-bold rounded-full">Major</span>@endif
                            </div>
                        </a>
                    @empty
                        <div class="bg-white rounded-2xl p-16 text-center shadow-sm"><i class="fa-solid fa-calendar text-5xl text-gray-200 mb-4"></i><h3 class="text-xl font-bold text-gray-900 mb-2">No Festivals</h3><p class="text-gray-500">Festival data will be added by admin.</p></div>
                    @endforelse
                </div>
                <div class="mt-6">{{ $festivals->links() }}</div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</body></html>
