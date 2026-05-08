@extends('admin.layout')
@section('title', 'Booking Management')
@section('page_title', 'Booking Management')
@section('content')

<!-- Status Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.bookings') }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ !request('status') ? 'bg-brand-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:border-brand-500' }}">All ({{ $counts['all'] }})</a>
    <a href="{{ route('admin.bookings', ['status'=>'pending']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='pending' ? 'bg-yellow-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Pending ({{ $counts['pending'] }})</a>
    <a href="{{ route('admin.bookings', ['status'=>'confirmed']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='confirmed' ? 'bg-green-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Confirmed ({{ $counts['confirmed'] }})</a>
    <a href="{{ route('admin.bookings', ['status'=>'completed']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='completed' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Completed ({{ $counts['completed'] }})</a>
    <a href="{{ route('admin.bookings', ['status'=>'cancelled']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='cancelled' ? 'bg-red-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Cancelled ({{ $counts['cancelled'] }})</a>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="text-xs uppercase text-gray-500 bg-gray-50/50">
                <th class="px-6 py-3 font-semibold border-b">ID</th>
                <th class="px-6 py-3 font-semibold border-b">Customer</th>
                <th class="px-6 py-3 font-semibold border-b">Pandit</th>
                <th class="px-6 py-3 font-semibold border-b">Service</th>
                <th class="px-6 py-3 font-semibold border-b">Date</th>
                <th class="px-6 py-3 font-semibold border-b">Status</th>
                <th class="px-6 py-3 font-semibold border-b">Payment</th>
                <th class="px-6 py-3 font-semibold border-b">Amount</th>
                <th class="px-6 py-3 font-semibold border-b">Actions</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50 transition text-sm">
                    <td class="px-6 py-3 font-medium text-gray-900">#BK-{{ str_pad($b->id,4,'0',STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-3 text-gray-600"><div class="flex items-center gap-2"><img src="{{ $b->user->avatar_url }}" class="w-7 h-7 rounded-full">{{ $b->user->name }}</div></td>
                    <td class="px-6 py-3 text-gray-600">{{ $b->pandit->user->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $b->service->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->status==='confirmed'?'bg-green-100 text-green-700':($b->status==='pending'?'bg-yellow-100 text-yellow-700':($b->status==='completed'?'bg-blue-100 text-blue-700':'bg-red-100 text-red-600')) }}">{{ ucfirst($b->status) }}</span></td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->payment_status==='paid'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">{{ ucfirst($b->payment_status) }}</span></td>
                    <td class="px-6 py-3 font-medium text-gray-900">₹{{ number_format($b->total_amount) }}</td>
                    <td class="px-6 py-3">
                        @if(!in_array($b->status, ['cancelled', 'completed']))
                            <form method="POST" action="{{ route('admin.booking.cancel', $b) }}" onsubmit="return confirm('Cancel this booking?')">@csrf<button class="text-red-500 hover:text-red-700 text-xs font-bold"><i class="fa-solid fa-xmark"></i> Cancel</button></form>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-12 text-center text-gray-400">No bookings found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
</div>
@endsection
