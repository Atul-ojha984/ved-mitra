<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $rashi->name }} Horoscope - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-[Inter] antialiased">
    @include('partials.navbar')

    <div class="bg-gradient-to-r from-purple-800 to-indigo-600 py-12 text-center text-white">
        <span class="text-6xl">{{ $rashi->symbol }}</span>
        <h1 class="text-3xl font-[Outfit] font-bold mt-3">{{ $rashi->name }} ({{ $rashi->hindi_name }})</h1>
        <p class="text-purple-200 mt-1">Element: {{ $rashi->element }} · {{ now()->format('d M Y') }}</p>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-10">
        <a href="{{ route('rashi.index') }}" class="text-purple-600 font-medium hover:underline text-sm mb-6 block"><i class="fa-solid fa-arrow-left mr-1"></i> All Rashis</a>

        <!-- Lucky Info -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border p-5 text-center"><p class="text-xs text-gray-400 uppercase font-bold mb-1">Lucky Color</p><p class="text-lg font-bold text-purple-600">{{ $rashi->lucky_color ?? '-' }}</p></div>
            <div class="bg-white rounded-2xl shadow-sm border p-5 text-center"><p class="text-xs text-gray-400 uppercase font-bold mb-1">Lucky Number</p><p class="text-lg font-bold text-purple-600">{{ $rashi->lucky_number ?? '-' }}</p></div>
        </div>

        <!-- Predictions -->
        <div class="space-y-6">
            @foreach([['Daily Prediction','daily_prediction','fa-sun','orange'],['Weekly Prediction','weekly_prediction','fa-calendar-week','blue'],['Monthly Prediction','monthly_prediction','fa-calendar','green'],['Career','career_prediction','fa-briefcase','indigo'],['Health','health_prediction','fa-heart-pulse','pink'],['Relationship','relationship_prediction','fa-heart','red']] as [$title,$field,$icon,$color])
                @if($rashi->$field)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fa-solid {{ $icon }} text-{{ $color }}-500 mr-2"></i>{{ $title }}</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $rashi->$field }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @include('partials.footer')
</body></html>
