@extends('pandit.layout')
@section('title', 'Earnings')
@section('page_title', 'Earnings & Revenue')
@section('content')

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-medium mb-1">Total Earnings</p>
        <h3 class="text-2xl font-bold text-green-600">₹{{ number_format($totalEarnings) }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-medium mb-1">This Month</p>
        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($monthEarnings) }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-medium mb-1">This Week</p>
        <h3 class="text-2xl font-bold text-gray-900">₹{{ number_format($weekEarnings) }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-xs font-medium mb-1">Platform Commission</p>
        <h3 class="text-2xl font-bold text-red-500">₹{{ number_format($totalCommission) }}</h3>
    </div>
</div>

<!-- Chart -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Monthly Revenue (Last 6 Months)</h3>
    <canvas id="revenueChart" height="200"></canvas>
</div>

<!-- Transactions -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Earning History</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="text-xs uppercase text-gray-500 bg-gray-50/50"><th class="px-6 py-3 font-semibold border-b">Booking</th><th class="px-6 py-3 font-semibold border-b">Customer</th><th class="px-6 py-3 font-semibold border-b">Gross</th><th class="px-6 py-3 font-semibold border-b">Commission</th><th class="px-6 py-3 font-semibold border-b">Net</th><th class="px-6 py-3 font-semibold border-b">Status</th><th class="px-6 py-3 font-semibold border-b">Date</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentEarnings as $e)
                <tr class="hover:bg-gray-50 transition text-sm">
                    <td class="px-6 py-3 font-medium text-gray-900">{{ $e->booking->service->name ?? 'Puja' }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $e->booking->user->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-600">₹{{ number_format($e->gross_amount) }}</td>
                    <td class="px-6 py-3 text-red-500">-₹{{ number_format($e->commission_amount) }}</td>
                    <td class="px-6 py-3 font-bold text-green-600">₹{{ number_format($e->net_amount) }}</td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $e->status==='paid'?'bg-green-100 text-green-700':'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($e->status) }}</span></td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $e->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No earnings yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $recentEarnings->links() }}</div>
</div>

<script>
new Chart(document.getElementById('revenueChart'), { type: 'bar', data: { labels: @json($chartLabels), datasets: [{ data: @json($chartData), backgroundColor: '#16a34a', borderRadius: 8 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } } } });
</script>
@endsection
