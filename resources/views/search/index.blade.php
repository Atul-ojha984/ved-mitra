<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Pandits - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], heading: ['Outfit', 'sans-serif'] },
                    colors: { brand: { 500: '#f97316', 600: '#ea580c', 900: '#7c2d12' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">

    @include('partials.navbar')

    <!-- Search Header -->
    <div class="bg-brand-900 py-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 flex justify-center items-center">
            <i class="fa-solid fa-om text-[400px] text-white"></i>
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <h1 class="text-4xl font-heading font-bold text-white mb-6">Find Nearby Pandits</h1>
            <form action="{{ route('pandit.search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 bg-white rounded-xl flex items-center px-4">
                    <i class="fa-solid fa-location-dot text-brand-500 mr-3"></i>
                    <input type="text" name="location" placeholder="City or Pincode" value="{{ request('location') }}" class="w-full py-4 border-none focus:ring-0 text-gray-700 bg-transparent outline-none">
                </div>
                <div class="flex-1 bg-white rounded-xl flex items-center px-4">
                    <i class="fa-solid fa-om text-brand-500 mr-3"></i>
                    <select name="service" class="w-full py-4 border-none focus:ring-0 text-gray-700 bg-transparent outline-none appearance-none">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->name }}" {{ request('service') == $service->name ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg">
                    <i class="fa-solid fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Results Area -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        <h2 class="text-2xl font-bold mb-8">Search Results ({{ $pandits->total() }})</h2>

        @if($pandits->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($pandits as $pandit)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition flex flex-col h-full">
                        <div class="h-48 bg-gray-200 relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pandit->user->name ?? 'Pandit') }}&background=ea580c&color=fff&size=512" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-gray-900 shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-star text-yellow-400"></i> 4.8
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $pandit->user->name ?? 'Pandit Ji' }}</h3>
                                @if($pandit->verified)
                                    <span class="text-blue-500" title="Verified Pandit"><i class="fa-solid fa-circle-check"></i></span>
                                @endif
                            </div>
                            <p class="text-gray-500 text-sm mb-4"><i class="fa-solid fa-briefcase text-gray-400 mr-1"></i> {{ $pandit->experience_years }} years experience</p>
                            
                            <div class="mb-6 flex-1">
                                <p class="text-sm text-gray-600 line-clamp-3">{{ $pandit->bio ?? 'Experienced Vedic Pandit for all religious ceremonies.' }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-100 mt-auto">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($pandit->services->take(3) as $service)
                                        <span class="text-xs font-medium bg-orange-50 text-brand-600 px-2 py-1 rounded-md">{{ $service->name }}</span>
                                    @endforeach
                                    @if($pandit->services->count() > 3)
                                        <span class="text-xs font-medium bg-gray-100 text-gray-600 px-2 py-1 rounded-md">+{{ $pandit->services->count() - 3 }} more</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="text-sm">
                                        <span class="text-gray-500 block">Starting from</span>
                                        <span class="font-bold text-lg text-gray-900">₹{{ number_format($pandit->services->first()->base_price ?? 1100) }}</span>
                                    </div>
                                    <a href="/booking/{{ $pandit->id }}" class="bg-brand-500 hover:bg-brand-600 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-md">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $pandits->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <i class="fa-solid fa-user-slash text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Pandits Found</h3>
                <p class="text-gray-500">Try adjusting your search criteria or location.</p>
            </div>
        @endif

    </main>

    @include('partials.footer')
</body>
</html>
