@extends('pandit.layout')
@section('title', 'My Bookings')
@section('page_title', 'Booking Management')
@section('content')

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('pandit.bookings') }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ !request('status') ? 'bg-brand-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">All ({{ $counts['all'] }})</a>
    <a href="{{ route('pandit.bookings', ['status'=>'pending']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='pending' ? 'bg-yellow-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Pending ({{ $counts['pending'] }})</a>
    <a href="{{ route('pandit.bookings', ['status'=>'confirmed']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='confirmed' ? 'bg-green-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Confirmed ({{ $counts['confirmed'] }})</a>
    <a href="{{ route('pandit.bookings', ['status'=>'completed']) }}" class="px-5 py-2 rounded-xl text-sm font-medium {{ request('status')==='completed' ? 'bg-blue-500 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200' }}">Completed ({{ $counts['completed'] }})</a>
</div>

<div class="space-y-4">
@forelse($bookings as $b)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $b->user->avatar_url }}" class="w-14 h-14 rounded-xl border-2 border-gray-100 object-cover">
                <div>
                    <h3 class="font-bold text-gray-900">{{ $b->user->name }}</h3>
                    <p class="text-sm text-gray-500"><i class="fa-solid fa-hands-praying mr-1 text-brand-500"></i> {{ $b->service->name ?? 'Puja' }}</p>
                    <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                        <span><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</span>
                        <span><i class="fa-regular fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($b->booking_time)->format('h:i A') }}</span>
                        @if($b->address)<span><i class="fa-solid fa-location-dot mr-1"></i>{{ \Illuminate\Support\Str::limit($b->address, 30) }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2 shrink-0">
                <span class="text-lg font-bold text-gray-900">₹{{ number_format($b->total_amount) }}</span>
                <div class="flex gap-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->status==='confirmed'?'bg-green-100 text-green-700':($b->status==='pending'?'bg-yellow-100 text-yellow-700':($b->status==='completed'?'bg-blue-100 text-blue-700':'bg-red-100 text-red-600')) }}">{{ ucfirst($b->status) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $b->payment_status==='paid'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">{{ ucfirst($b->payment_status) }}</span>
                </div>
                @if($b->status === 'pending')
                <div class="flex gap-2 mt-1">
                    <form method="POST" action="{{ route('pandit.booking.accept', $b) }}">@csrf<button class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1.5 px-4 rounded-lg transition"><i class="fa-solid fa-check mr-1"></i>Accept</button></form>
                    <form method="POST" action="{{ route('pandit.booking.reject', $b) }}">@csrf<button class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-4 rounded-lg transition"><i class="fa-solid fa-xmark mr-1"></i>Reject</button></form>
                </div>
                @elseif($b->status === 'confirmed')
                <form method="POST" action="{{ route('pandit.booking.complete', $b) }}">@csrf<button class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1.5 px-4 rounded-lg transition mt-1"><i class="fa-solid fa-flag-checkered mr-1"></i>Mark Complete</button></form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100"><i class="fa-regular fa-calendar-check text-5xl text-gray-200 mb-4"></i><h3 class="text-xl font-bold text-gray-900 mb-2">No Bookings</h3><p class="text-gray-500">You'll see bookings here when customers book your services.</p></div>
@endforelse
</div>
<div class="mt-6">{{ $bookings->links() }}</div>
@endsection
