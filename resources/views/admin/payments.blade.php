@extends('admin.layout')
@section('title', 'Payment Management')
@section('page_title', 'Payment Management')
@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm font-medium mb-1">Total Revenue</p>
        <h3 class="text-3xl font-bold text-green-600">₹{{ number_format($stats['total']) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm font-medium mb-1">Successful Payments</p>
        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['count'] }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm font-medium mb-1">Pending Amount</p>
        <h3 class="text-3xl font-bold text-yellow-600">₹{{ number_format($stats['pending']) }}</h3>
    </div>
</div>

<!-- Filter -->
<div class="flex gap-2 mb-6">
    <a href="{{ route('admin.payments') }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ !request('status') ? 'bg-brand-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">All</a>
    <a href="{{ route('admin.payments', ['status'=>'successful']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='successful' ? 'bg-green-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Successful</a>
    <a href="{{ route('admin.payments', ['status'=>'pending']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='pending' ? 'bg-yellow-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Pending</a>
    <a href="{{ route('admin.payments', ['status'=>'failed']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='failed' ? 'bg-red-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Failed</a>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="text-xs uppercase text-gray-500 bg-gray-50/50">
                <th class="px-6 py-3 font-semibold border-b">Transaction ID</th>
                <th class="px-6 py-3 font-semibold border-b">Customer</th>
                <th class="px-6 py-3 font-semibold border-b">Booking</th>
                <th class="px-6 py-3 font-semibold border-b">Method</th>
                <th class="px-6 py-3 font-semibold border-b">Amount</th>
                <th class="px-6 py-3 font-semibold border-b">Status</th>
                <th class="px-6 py-3 font-semibold border-b">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $pay)
                <tr class="hover:bg-gray-50 transition text-sm">
                    <td class="px-6 py-3 font-mono text-xs text-gray-700">{{ $pay->transaction_id ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $pay->booking->user->name ?? '-' }}</td>
                    <td class="px-6 py-3 font-medium text-gray-900">#BK-{{ str_pad($pay->booking_id,4,'0',STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 uppercase">{{ $pay->payment_method ?? 'N/A' }}</span></td>
                    <td class="px-6 py-3 font-medium text-gray-900">₹{{ number_format($pay->amount) }}</td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $pay->status==='successful'?'bg-green-100 text-green-700':($pay->status==='pending'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') }}">{{ ucfirst($pay->status) }}</span></td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $pay->created_at->format('d M Y, h:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $payments->links() }}</div>
</div>
@endsection
