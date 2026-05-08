<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-gray-50 flex items-center justify-center min-h-screen p-6 font-[Inter]">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-[Outfit] font-bold text-gray-900 inline-flex items-center gap-2">
                <i class="fa-solid fa-om text-orange-500"></i> {{ config('app.name', 'Ved Mitra') }}
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Reset Password</h1>
                <p class="text-gray-500 text-sm">Enter your new password below</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-3 outline-none text-gray-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Min 8 characters" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" placeholder="Re-enter password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 outline-none transition">
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl py-3.5 transition shadow-lg shadow-orange-500/30">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>
