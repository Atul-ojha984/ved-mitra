@extends('pandit.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('content')

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-brand-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-xs font-medium mb-1">Total Earnings</p><h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($totalEarnings) }}</h3><p class="text-green-500 text-xs mt-1 font-medium">₹{{ number_format($monthEarnings) }} this month</p></div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-xs font-medium mb-1">Total Bookings</p><h3 class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</h3><p class="text-blue-500 text-xs mt-1 font-medium">{{ $completedBookings }} completed</p></div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-yellow-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-xs font-medium mb-1">Pending Bookings</p><h3 class="text-2xl font-bold text-orange-600">{{ $pendingBookings }}</h3></div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-xs font-medium mb-1">Rating</p><h3 class="text-2xl font-bold text-gray-900">{{ number_format($avgRating, 1) }} <i class="fa-solid fa-star text-yellow-400 text-sm"></i></h3><p class="text-gray-400 text-xs mt-1">{{ $reviewCount }} reviews</p></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Today's Schedule -->
    <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4"><i class="fa-solid fa-calendar-day text-brand-500 mr-2"></i> Today's Schedule</h3>
        @forelse($todayBookings as $b)
            <div class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="w-10 h-10 rounded-xl {{ $b->status==='confirmed'?'bg-green-100 text-green-600':'bg-yellow-100 text-yellow-600' }} flex items-center justify-center text-xs font-bold shrink-0">{{ \Carbon\Carbon::parse($b->booking_time)->format('h A') }}</div>
                <div class="min-w-0"><p class="font-medium text-gray-900 text-sm truncate">{{ $b->service->name ?? 'Puja' }}</p><p class="text-xs text-gray-500">{{ $b->user->name }}</p></div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400"><i class="fa-regular fa-calendar-check text-3xl mb-2"></i><p class="text-sm">No bookings today</p></div>
        @endforelse
    </div>

    <!-- Earnings Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4"><i class="fa-solid fa-chart-line text-green-500 mr-2"></i> Earnings — Last 7 Days</h3>
        <canvas id="earningsChart" height="160"></canvas>
    </div>
</div>

<!-- Upcoming Bookings -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"><h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Upcoming Bookings</h3><a href="{{ route('pandit.bookings') }}" class="text-brand-600 text-sm font-medium hover:underline">View All</a></div>
    <div class="divide-y divide-gray-100">
        @forelse($upcomingBookings as $b)
        <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
            <div class="flex items-center gap-4">
                <img src="{{ $b->user->avatar_url }}" class="w-10 h-10 rounded-full">
                <div><p class="font-medium text-gray-900 text-sm">{{ $b->user->name }}</p><p class="text-xs text-gray-500">{{ $b->service->name ?? '-' }} · {{ \Carbon\Carbon::parse($b->booking_date)->format('d M') }} at {{ \Carbon\Carbon::parse($b->booking_time)->format('h:i A') }}</p></div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->status==='confirmed'?'bg-green-100 text-green-700':'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($b->status) }}</span>
                <span class="text-sm font-bold text-gray-900">₹{{ number_format($b->total_amount) }}</span>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-400">No upcoming bookings</div>
        @endforelse
    </div>
</div>

<script>
new Chart(document.getElementById('earningsChart'), { type: 'line', data: { labels: @json($chartLabels), datasets: [{ data: @json($chartEarnings), borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.08)', fill: true, tension: 0.4, pointBackgroundColor: '#16a34a', borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } } });
</script>
@endsection
