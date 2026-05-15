<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book {{ $pandit->user->name ?? 'Pandit' }} - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    <div class="max-w-4xl mx-auto px-6 py-12">
        
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold font-heading">Book Spiritual Service</h1>
            <a href="{{ route('pandit.search') }}" class="text-orange-600 hover:underline"><i class="fa-solid fa-arrow-left mr-2"></i>Back to Search</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Pandit Info -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pandit->user->name ?? 'P') }}&background=ea580c&color=fff&size=512" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-1">{{ $pandit->user->name ?? 'Pandit Ji' }}</h2>
                        <p class="text-sm text-gray-500 mb-4"><i class="fa-solid fa-briefcase mr-1"></i> {{ $pandit->experience_years }} Years Experience</p>
                        <p class="text-sm text-gray-700">{{ $pandit->bio }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('booking.store', $pandit) }}" method="POST" data-secure-form novalidate>
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Service / Puja</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($pandit->services as $service)
                                    <label class="relative flex cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm focus:outline-none hover:border-orange-500 transition">
                                        <input type="radio" name="service_id" value="{{ $service->id }}" class="sr-only peer" required {{ (string) old('service_id') === (string) $service->id ? 'checked' : '' }}>
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span class="block text-sm font-medium text-gray-900">{{ $service->name }}</span>
                                                <span class="mt-1 flex items-center text-sm text-gray-500">{{ $service->duration_hours }} Hours</span>
                                            </span>
                                        </span>
                                        <i class="fa-solid fa-circle-check text-orange-500 text-xl hidden peer-checked:block"></i>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                                <input type="date" name="booking_date" value="{{ old('booking_date') }}" required min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Time</label>
                                <select name="booking_time" required class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none">
                                    <option value="">Choose a slot...</option>
                                    <option value="08:00:00" {{ old('booking_time') === '08:00:00' ? 'selected' : '' }}>08:00 AM</option>
                                    <option value="10:00:00" {{ old('booking_time') === '10:00:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="12:00:00" {{ old('booking_time') === '12:00:00' ? 'selected' : '' }}>12:00 PM</option>
                                    <option value="15:00:00" {{ old('booking_time') === '15:00:00' ? 'selected' : '' }}>03:00 PM</option>
                                    <option value="17:00:00" {{ old('booking_time') === '17:00:00' ? 'selected' : '' }}>05:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Address</label>
                            <textarea name="address" required rows="3" placeholder="Enter complete address..." class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none">{{ old('address') }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-orange-500/30 transition text-lg flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i> Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.form-security')
</body>
</html>
