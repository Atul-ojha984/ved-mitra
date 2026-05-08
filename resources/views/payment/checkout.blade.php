<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if($paymentMode === 'razorpay')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-[Inter]">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-lg">

            <!-- Booking Summary Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-8 py-6 text-white">
                    <h1 class="text-2xl font-bold font-[Outfit]">Complete Payment</h1>
                    <p class="text-orange-100 text-sm mt-1">
                        @if($paymentMode === 'razorpay')
                            Secure payment powered by Razorpay
                        @else
                            Secure payment — select a method below
                        @endif
                    </p>
                </div>

                <div class="p-8">
                    <!-- Pandit Info -->
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->pandit->user->name ?? 'P') }}&background=ea580c&color=fff&size=128" class="w-14 h-14 rounded-full border-2 border-white shadow-md" alt="Pandit">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $booking->pandit->user->name ?? 'Pandit Ji' }}</h3>
                            <p class="text-sm text-gray-500">{{ $booking->service->name ?? 'Puja Service' }}</p>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Booking ID</span>
                            <span class="font-medium text-gray-900">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Date</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Time</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-4 border-t border-dashed border-gray-200">
                            <span class="text-gray-700 font-bold text-base">Total Amount</span>
                            <span class="font-bold text-xl text-orange-600">₹{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- ═══ RAZORPAY MODE ═══ --}}
                    @if($paymentMode === 'razorpay' && $razorpayOrder)
                        <button id="rzp-button" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-600/30 transition text-lg flex items-center justify-center gap-3">
                            <i class="fa-solid fa-shield-halved"></i> Pay ₹{{ number_format($booking->total_amount, 2) }}
                        </button>

                    {{-- ═══ DEMO / DIRECT MODE ═══ --}}
                    @else
                        <div x-data="{ method: 'upi' }" class="space-y-4">
                            <p class="text-sm font-bold text-gray-700 mb-2">Select Payment Method</p>

                            <!-- Payment Method Tabs -->
                            <div class="grid grid-cols-2 gap-3">
                                <button @click="method = 'upi'" :class="method === 'upi' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-gray-50 border-gray-200 text-gray-600'" class="flex items-center gap-3 p-4 rounded-xl border-2 transition cursor-pointer">
                                    <i class="fa-solid fa-mobile-screen-button text-lg"></i>
                                    <span class="font-medium text-sm">UPI</span>
                                </button>
                                <button @click="method = 'card'" :class="method === 'card' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-gray-50 border-gray-200 text-gray-600'" class="flex items-center gap-3 p-4 rounded-xl border-2 transition cursor-pointer">
                                    <i class="fa-regular fa-credit-card text-lg"></i>
                                    <span class="font-medium text-sm">Card</span>
                                </button>
                                <button @click="method = 'netbanking'" :class="method === 'netbanking' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-gray-50 border-gray-200 text-gray-600'" class="flex items-center gap-3 p-4 rounded-xl border-2 transition cursor-pointer">
                                    <i class="fa-solid fa-building-columns text-lg"></i>
                                    <span class="font-medium text-sm">Net Banking</span>
                                </button>
                                <button @click="method = 'cod'" :class="method === 'cod' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-gray-50 border-gray-200 text-gray-600'" class="flex items-center gap-3 p-4 rounded-xl border-2 transition cursor-pointer">
                                    <i class="fa-solid fa-hand-holding-dollar text-lg"></i>
                                    <span class="font-medium text-sm">Pay Later</span>
                                </button>
                            </div>

                            <form method="POST" action="{{ route('payment.demo', $booking) }}">
                                @csrf
                                <input type="hidden" name="payment_method" x-bind:value="method">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-600/30 transition text-lg flex items-center justify-center gap-3 mt-2">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span x-text="method === 'cod' ? 'Confirm Booking (Pay Later)' : 'Pay ₹{{ number_format($booking->total_amount, 2) }}'"></span>
                                </button>
                            </form>
                        </div>
                    @endif

                    <p class="text-center text-xs text-gray-400 mt-4">
                        <i class="fa-solid fa-lock mr-1"></i> Secured by 256-bit SSL encryption
                    </p>
                </div>
            </div>

            <a href="{{ route('user.dashboard') }}" class="block text-center mt-6 text-gray-500 text-sm hover:text-orange-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i> Cancel & return to dashboard</a>
        </div>
    </div>

    {{-- Razorpay JS — only rendered when in Razorpay mode --}}
    @if($paymentMode === 'razorpay' && $razorpayOrder)
    <script>
        var options = {
            "key": "{{ config('services.razorpay.key') }}",
            "amount": "{{ $razorpayOrder['amount'] }}",
            "currency": "INR",
            "name": "{{ config('app.name', 'Ved Mitra') }}",
            "description": "Booking #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}",
            "order_id": "{{ $razorpayOrder['id'] }}",
            "handler": function (response) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("payment.verify") }}';
                var token = document.createElement('input');
                token.type = 'hidden'; token.name = '_token'; token.value = '{{ csrf_token() }}';
                form.appendChild(token);
                var fields = {
                    'razorpay_payment_id': response.razorpay_payment_id,
                    'razorpay_order_id': response.razorpay_order_id,
                    'razorpay_signature': response.razorpay_signature,
                };
                for (var key in fields) {
                    var input = document.createElement('input');
                    input.type = 'hidden'; input.name = key; input.value = fields[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            },
            "prefill": { "name": "{{ auth()->user()->name }}", "email": "{{ auth()->user()->email }}" },
            "theme": { "color": "#f97316" }
        };
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button').onclick = function(e) { rzp.open(); e.preventDefault(); };
    </script>
    @endif

</body>
</html>
