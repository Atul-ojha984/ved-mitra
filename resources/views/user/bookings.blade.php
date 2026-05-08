<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-[Inter]">

    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-[Outfit] font-bold text-gray-900 mb-8">My Bookings</h1>

        @php
            $bookings = auth()->user()->bookings()
                ->with(['pandit.user', 'service'])
                ->orderByDesc('created_at')
                ->paginate(10);
        @endphp

        @forelse($bookings as $booking)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-4 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl {{ $booking->status === 'confirmed' || $booking->status === 'completed' ? 'bg-green-50 text-green-500' : ($booking->status === 'cancelled' ? 'bg-red-50 text-red-400' : 'bg-yellow-50 text-yellow-500') }} flex items-center justify-center text-xl">
                            <i class="fa-solid {{ $booking->status === 'confirmed' || $booking->status === 'completed' ? 'fa-circle-check' : ($booking->status === 'cancelled' ? 'fa-circle-xmark' : 'fa-clock') }}"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                            <p class="text-sm text-gray-500">
                                #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }} ·
                                {{ $booking->pandit->user->name ?? 'Pandit' }} ·
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        <span class="text-lg font-bold text-gray-900">₹{{ number_format($booking->total_amount) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
                <i class="fa-regular fa-calendar-xmark text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No bookings yet</h3>
                <p class="text-gray-500 mb-6">Start by finding a Pandit for your next spiritual ceremony.</p>
                <a href="{{ route('pandit.search') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-orange-500/30">Find a Pandit</a>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    </main>

</body>
</html>
