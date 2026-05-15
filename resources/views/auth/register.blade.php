<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-gray-50 flex items-center justify-center min-h-screen p-6 font-[Inter]">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-[Outfit] font-bold text-gray-900 inline-flex items-center gap-2">
                <i class="fa-solid fa-om text-orange-500"></i> {{ config('app.name', 'Ved Mitra') }}
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Create Your Account</h1>
                <p class="text-gray-500 text-sm">Join to book spiritual services instantly</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" data-secure-form novalidate>
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name" class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required data-indian-phone inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" autocomplete="tel-national" placeholder="9876543210" class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition {{ $errors->has('phone') ? 'form-input-error' : '' }}">
                    </div>
                    @error('phone')
                        <p class="form-field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" required minlength="8" placeholder="Min 8 characters" class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password_confirmation" required minlength="8" placeholder="Re-enter password" class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                    </div>
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl py-3.5 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="flex items-center gap-3 my-6"><hr class="flex-1 border-gray-200"><span class="text-xs text-gray-400 font-medium">OR</span><hr class="flex-1 border-gray-200"></div>

            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 rounded-xl border border-gray-200 transition shadow-sm">
                <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Sign up with Google
            </a>

            <p class="text-center mt-6 text-gray-500 text-sm">
                Already have an account? <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">Log in</a>
            </p>
            <div class="border-t border-gray-100 mt-6 pt-5 text-center">
                <p class="text-gray-500 text-sm mb-2">Are you a Pandit?</p>
                <a href="{{ route('pandit.register') }}" class="text-orange-600 font-bold hover:underline text-sm inline-flex items-center gap-1">
                    <i class="fa-solid fa-user-tie"></i> Register as Pandit →
                </a>
            </div>
        </div>
    </div>
    @include('partials.form-security')
</body>
</html>
