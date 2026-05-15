<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Dashboard - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-[Inter]">

    @include('partials.navbar')

    <main class="max-w-7xl mx-auto px-6 py-12">

        <!-- Welcome Banner — uses auth()->user()->name dynamically -->
        <div class="bg-gradient-to-br from-orange-600 via-orange-500 to-amber-500 rounded-3xl p-8 mb-12 relative overflow-hidden text-white shadow-2xl">
            <div class="absolute -right-20 -top-20 opacity-10">
                <i class="fa-solid fa-om text-[300px]"></i>
            </div>
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-3xl md:text-4xl font-[Outfit] font-bold mb-3">Namaste, {{ auth()->user()->name }}! 🙏</h1>
                <p class="text-orange-100 text-lg mb-8">Ready for your next spiritual ceremony? Manage bookings, explore services, and track payments.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('pandit.search') }}" class="bg-white text-orange-600 hover:bg-gray-50 font-bold py-3 px-6 rounded-xl transition shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-search"></i> Book a Pandit
                    </a>
                    <a href="{{ route('profile.show') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur border border-white/30 font-medium py-3 px-6 rounded-xl transition text-white flex items-center gap-2">
                        <i class="fa-regular fa-user"></i> My Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ─── Upcoming Bookings (2 columns) ─── -->
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-2xl font-bold font-[Outfit] text-gray-900 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-orange-500"></i> Upcoming Bookings
                </h2>

                @php
                    $upcoming = auth()->user()->bookings()
                        ->with(['pandit.user', 'service'])
                        ->where('booking_date', '>=', now()->toDateString())
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->orderBy('booking_date')
                        ->get();
                @endphp

                @forelse($upcoming as $booking)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 text-2xl">
                                    <i class="fa-solid fa-om"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                                    <p class="text-sm text-gray-500">Booking ID: #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full font-semibold text-sm border
                                {{ $booking->status === 'confirmed'
                                    ? 'bg-green-50 text-green-700 border-green-100'
                                    : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                                <i class="fa-solid {{ $booking->status === 'confirmed' ? 'fa-circle-check' : 'fa-clock' }}"></i>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 rounded-xl p-4 mb-6">
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Date</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Time</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Pandit</p>
                                <p class="font-semibold text-orange-600">{{ $booking->pandit->user->name ?? 'Pandit Ji' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Amount</p>
                                <p class="font-semibold text-gray-900">₹{{ number_format($booking->total_amount) }}
                                    <span class="text-xs ml-1 {{ $booking->payment_status === 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                                        ({{ ucfirst($booking->payment_status) }})
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($booking->status === 'pending' && $booking->payment_status === 'pending')
                            <a href="{{ route('payment.checkout', $booking) }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-md">
                                <i class="fa-solid fa-credit-card"></i> Complete Payment
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-3xl">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No upcoming bookings</h3>
                        <p class="text-gray-500 mb-6">You don't have any spiritual ceremonies scheduled yet.</p>
                        <a href="{{ route('pandit.search') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2.5 px-6 rounded-xl transition shadow-lg shadow-orange-500/30">Book a Service</a>
                    </div>
                @endforelse
            </div>

            <!-- ─── Sidebar ─── -->
            <div class="space-y-8">
                <!-- Dynamic Profile Summary -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-white shadow-md object-cover" alt="{{ auth()->user()->name }}">
                    <h3 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->phone)
                        <p class="text-gray-400 text-sm mt-1"><i class="fa-solid fa-phone text-xs mr-1"></i> {{ auth()->user()->phone }}</p>
                    @endif
                    <a href="{{ route('profile.show') }}" class="mt-4 inline-block w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 rounded-lg transition border border-gray-200">Edit Profile</a>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-4 uppercase tracking-wider text-sm">Quick Links</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('my.bookings') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-orange-50 text-gray-700 hover:text-orange-600 transition group">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-clock-rotate-left w-5 text-gray-400 group-hover:text-orange-500"></i> Booking History</span>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.show') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-orange-50 text-gray-700 hover:text-orange-600 transition group">
                                <span class="flex items-center gap-3"><i class="fa-regular fa-user w-5 text-gray-400 group-hover:text-orange-500"></i> My Profile</span>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pandit.search') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-orange-50 text-gray-700 hover:text-orange-600 transition group">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-search w-5 text-gray-400 group-hover:text-orange-500"></i> Find a Pandit</span>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 text-red-600 transition w-full text-left mt-2">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-red-400"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </main>

@include('partials.form-security')
</body>
</html>
