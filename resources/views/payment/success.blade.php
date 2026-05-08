<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmed - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes checkmark { 0% { transform: scale(0) rotate(-45deg); opacity: 0; } 60% { transform: scale(1.2) rotate(0deg); } 100% { transform: scale(1) rotate(0deg); opacity: 1; } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-check { animation: checkmark 0.6s ease-out forwards; }
        .animate-fade-up { animation: fadeUp 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-[Inter]">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

                <!-- Success Header -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-8 py-10 text-center text-white relative">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl animate-check">
                        <i class="fa-solid fa-check text-green-500 text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold font-[Outfit] animate-fade-up">Booking Confirmed!</h1>
                    <p class="text-green-100 mt-2 animate-fade-up" style="animation-delay: 0.1s">Your spiritual service has been booked successfully</p>
                </div>

                <!-- Booking Details -->
                <div class="p-8 animate-fade-up" style="animation-delay: 0.2s">
                    <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
                        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-200">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->pandit->user->name ?? 'P') }}&background=ea580c&color=fff&size=128" class="w-14 h-14 rounded-full border-2 border-white shadow" alt="Pandit">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $booking->pandit->user->name ?? 'Pandit Ji' }}</h3>
                                <p class="text-sm text-orange-600 font-medium">{{ $booking->service->name ?? 'Puja Service' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-y-4 text-sm">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Booking ID</p>
                                <p class="font-bold text-gray-900">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Status</p>
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-bold"><i class="fa-solid fa-circle-check"></i> Confirmed</span>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Date</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Time</p>
                                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold mb-1">Amount Paid</p>
                                <p class="font-bold text-2xl text-green-600">₹{{ number_format($booking->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('user.dashboard') }}" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl text-center transition shadow-lg shadow-orange-500/30">
                            <i class="fa-solid fa-gauge-high mr-2"></i>My Dashboard
                        </a>
                        <a href="/" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-center transition">
                            <i class="fa-solid fa-house mr-2"></i>Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
