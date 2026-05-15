<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 font-[Inter] min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 text-3xl font-[Outfit] font-bold text-white mb-2">
                <i class="fa-solid fa-om text-orange-500"></i> {{ config('app.name', 'Ved Mitra') }}
            </div>
            <p class="text-gray-400 text-sm">Admin Control Panel</p>
        </div>

        <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 p-8">
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-orange-500"></i> Admin Login
            </h2>

            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5" data-secure-form novalidate>
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition placeholder-gray-500" placeholder="admin@vedmitra.in">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition placeholder-gray-500" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-orange-500/30 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In
                </button>
            </form>
        </div>
        <p class="text-center text-gray-600 text-xs mt-6">© {{ date('Y') }} {{ config('app.name', 'Ved Mitra') }}. All rights reserved.</p>
    </div>
    @include('partials.form-security')
</body>
</html>
