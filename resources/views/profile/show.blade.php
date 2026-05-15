<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-[Inter]">

    @include('partials.navbar')

    <main class="max-w-5xl mx-auto px-6 py-12">

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-8 text-sm border border-green-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- ─── Profile Card ─── -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-24 relative"></div>
                    <div class="px-6 pb-6 -mt-12 text-center">
                        <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg mx-auto object-cover" alt="{{ $user->name }}">
                        <h2 class="text-xl font-bold text-gray-900 mt-3">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-gray-500 text-sm mt-1"><i class="fa-solid fa-phone text-xs mr-1"></i> {{ $user->phone }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Stats</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-orange-50 rounded-xl">
                            <p class="text-2xl font-bold text-orange-600">{{ $upcomingBookings->count() }}</p>
                            <p class="text-xs text-gray-500">Upcoming</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-xl">
                            <p class="text-2xl font-bold text-green-600">{{ $pastBookings->where('status', 'completed')->count() }}</p>
                            <p class="text-xs text-gray-500">Completed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ─── Profile Edit & Bookings ─── -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Edit Profile Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2"><i class="fa-regular fa-pen-to-square text-orange-500"></i> Edit Profile</h3>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" data-secure-form novalidate>
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" data-indian-phone inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" autocomplete="tel-national" placeholder="9876543210" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition {{ $errors->has('phone') ? 'form-input-error' : '' }}">
                                @error('phone')
                                    <p class="form-field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-400 mt-1">Email cannot be changed</p>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                            <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp" data-max-mb="2" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition">
                        </div>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-orange-500/30">
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2"><i class="fa-regular fa-calendar text-orange-500"></i> Upcoming Bookings</h3>

                    @forelse($upcomingBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-3 last:mb-0 hover:bg-orange-50/50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500">
                                    <i class="fa-solid fa-om"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->pandit->user->name ?? 'Pandit' }} · {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($booking->total_amount) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fa-regular fa-calendar-xmark text-3xl mb-3"></i>
                            <p class="font-medium">No upcoming bookings</p>
                            <a href="{{ route('pandit.search') }}" class="text-orange-600 text-sm font-medium hover:underline mt-2 inline-block">Book a Pandit →</a>
                        </div>
                    @endforelse
                </div>

                <!-- Booking History -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-orange-500"></i> Booking History</h3>

                    @forelse($pastBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-3 last:mb-0">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-om"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <p class="text-sm font-bold text-gray-900 mt-1">₹{{ number_format($booking->total_amount) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fa-solid fa-clock-rotate-left text-3xl mb-3"></i>
                            <p class="font-medium">No past bookings yet</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </main>

@include('partials.form-security')
</body>
</html>
