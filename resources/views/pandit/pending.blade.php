<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Pending - {{ config('app.name', 'Ved Mitra') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes pulse-slow { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 font-[Inter] min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Status Header -->
            <div class="px-8 py-10 text-center
                @if($profile && $profile->approval_status === 'rejected')
                    bg-gradient-to-br from-red-500 to-red-600
                @else
                    bg-gradient-to-br from-amber-500 to-orange-500
                @endif">

                @if($profile && $profile->approval_status === 'rejected')
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-circle-xmark text-4xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-[Outfit] font-bold text-white">Application Rejected</h1>
                    <p class="text-red-100 mt-2">Unfortunately, your application was not approved</p>
                @else
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse-slow">
                        <i class="fa-solid fa-clock text-4xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-[Outfit] font-bold text-white">Application Under Review</h1>
                    <p class="text-orange-100 mt-2">Your pandit registration is being reviewed by our team</p>
                @endif
            </div>

            <div class="p-8">
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl mb-6 border border-gray-100">
                    <img src="{{ $user->avatar_url }}" class="w-14 h-14 rounded-full border-2 border-white shadow" alt="{{ $user->name }}">
                    <div>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                @if($profile && $profile->approval_status === 'rejected' && $profile->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <p class="text-sm font-bold text-red-700 mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Reason for Rejection:</p>
                        <p class="text-sm text-red-600">{{ $profile->rejection_reason }}</p>
                    </div>
                @endif

                @if(!$profile || $profile->approval_status !== 'rejected')
                    <!-- Progress Steps -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Application Submitted</p>
                                <p class="text-xs text-gray-500">Your details have been received</p>
                            </div>
                        </div>
                        <div class="ml-5 h-6 border-l-2 border-dashed border-amber-300"></div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0 animate-pulse-slow">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Under Admin Review</p>
                                <p class="text-xs text-gray-500">Documents and qualifications being verified</p>
                            </div>
                        </div>
                        <div class="ml-5 h-6 border-l-2 border-dashed border-gray-200"></div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-400 text-sm">Approval & Dashboard Access</p>
                                <p class="text-xs text-gray-400">Pending admin decision</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex gap-3">
                    <a href="/" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-center transition">
                        <i class="fa-solid fa-house mr-1"></i> Home
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 rounded-xl transition">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
