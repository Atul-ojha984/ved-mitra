@extends('admin.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('content')

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-sm font-medium mb-1">Total Users</p><h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</h3></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-sm font-medium mb-1">Approved Pandits</p><h3 class="text-3xl font-bold text-gray-900">{{ $totalPandits }}</h3></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-sm font-medium mb-1">Total Bookings</p><h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalBookings) }}</h3><p class="text-green-500 text-xs mt-1 font-medium">{{ $todayBookings }} today</p></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10"><p class="text-gray-500 text-sm font-medium mb-1">Total Revenue</p><h3 class="text-3xl font-bold text-gray-900">₹{{ number_format($totalRevenue) }}</h3><p class="text-green-500 text-xs mt-1 font-medium">₹{{ number_format($monthRevenue) }} this month</p></div>
    </div>
</div>

<!-- Pending Alert -->
@if($pendingApprovals > 0)
    <a href="{{ route('admin.pandits', ['status' => 'pending']) }}" class="block bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-8 hover:bg-amber-100 transition group">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition"><i class="fa-solid fa-user-clock"></i></div>
            <div><p class="font-bold text-amber-800">{{ $pendingApprovals }} Pandit Registration{{ $pendingApprovals > 1 ? 's' : '' }} Pending</p><p class="text-sm text-amber-600">Click to review →</p></div>
        </div>
    </a>
@endif

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Bookings — Last 7 Days</h3>
        <canvas id="bookingsChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Revenue — Last 7 Days</h3>
        <canvas id="revenueChart" height="200"></canvas>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"><h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Recent Bookings</h3><a href="{{ route('admin.bookings') }}" class="text-brand-600 text-sm font-medium hover:underline">View All</a></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left"><thead><tr class="text-xs uppercase text-gray-500"><th class="px-6 py-3 font-semibold border-b">ID</th><th class="px-6 py-3 font-semibold border-b">Customer</th><th class="px-6 py-3 font-semibold border-b">Service</th><th class="px-6 py-3 font-semibold border-b">Status</th><th class="px-6 py-3 font-semibold border-b">Amount</th></tr></thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($recentBookings as $b)
            <tr class="hover:bg-gray-50 transition text-sm">
                <td class="px-6 py-3 font-medium text-gray-900">#BK-{{ str_pad($b->id,4,'0',STR_PAD_LEFT) }}</td>
                <td class="px-6 py-3 text-gray-600"><div class="flex items-center gap-2"><img src="{{ $b->user->avatar_url }}" class="w-7 h-7 rounded-full">{{ $b->user->name }}</div></td>
                <td class="px-6 py-3 text-gray-600">{{ $b->service->name ?? '-' }}</td>
                <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->status==='confirmed'?'bg-green-100 text-green-700':($b->status==='pending'?'bg-yellow-100 text-yellow-700':($b->status==='completed'?'bg-blue-100 text-blue-700':'bg-red-100 text-red-600')) }}">{{ ucfirst($b->status) }}</span></td>
                <td class="px-6 py-3 font-medium text-gray-900">₹{{ number_format($b->total_amount) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No bookings yet</td></tr>
            @endforelse
        </tbody></table>
    </div>
</div>

<script>
const chartDefaults = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } };
new Chart(document.getElementById('bookingsChart'), { type: 'bar', data: { labels: @json($chartLabels), datasets: [{ data: @json($chartBookings), backgroundColor: '#f97316', borderRadius: 8 }] }, options: chartDefaults });
new Chart(document.getElementById('revenueChart'), { type: 'line', data: { labels: @json($chartLabels), datasets: [{ data: @json($chartRevenue), borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.1)', fill: true, tension: 0.4, pointBackgroundColor: '#16a34a' }] }, options: chartDefaults });
</script>
@endsection
