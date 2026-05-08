<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Rashi Bhavishya - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-[Inter] antialiased">
    @include('partials.navbar')

    <div class="bg-gradient-to-r from-purple-800 to-indigo-600 py-16 text-center text-white">
        <h1 class="text-4xl font-[Outfit] font-bold mb-2"><i class="fa-solid fa-star mr-2"></i> Daily Rashi Bhavishya</h1>
        <p class="text-purple-200">Today's Horoscope · {{ now()->format('d M Y, l') }}</p>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
        @if($rashis->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($rashis as $r)
                @php $colors = ['Aries'=>'red','Taurus'=>'green','Gemini'=>'yellow','Cancer'=>'blue','Leo'=>'orange','Virgo'=>'emerald','Libra'=>'pink','Scorpio'=>'rose','Sagittarius'=>'purple','Capricorn'=>'gray','Aquarius'=>'cyan','Pisces'=>'indigo']; $c = $colors[$r->name] ?? 'orange'; @endphp
                    <a href="{{ route('rashi.show', $r) }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="bg-{{ $c }}-50 p-6 text-center group-hover:bg-{{ $c }}-100 transition">
                            <span class="text-4xl">{{ $r->symbol }}</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-2">{{ $r->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $r->hindi_name }}</p>
                        </div>
                        <div class="p-5">
                            @if($r->daily_prediction)<p class="text-gray-600 text-sm line-clamp-3">{{ $r->daily_prediction }}</p>@endif
                            <div class="flex gap-3 mt-4 text-xs">
                                @if($r->lucky_color)<span class="bg-gray-100 px-2 py-1 rounded-md font-medium text-gray-600"><i class="fa-solid fa-palette mr-1"></i>{{ $r->lucky_color }}</span>@endif
                                @if($r->lucky_number)<span class="bg-gray-100 px-2 py-1 rounded-md font-medium text-gray-600"><i class="fa-solid fa-hashtag mr-1"></i>{{ $r->lucky_number }}</span>@endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-16 text-center shadow-sm border">
                <i class="fa-solid fa-star text-5xl text-gray-200 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Horoscope Coming Soon</h3>
                <p class="text-gray-500">Daily predictions will be updated by admin.</p>
            </div>
        @endif
    </div>
    @include('partials.footer')
</body></html>
