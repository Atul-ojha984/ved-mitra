@extends('admin.layout')
@section('title', 'Pandit Management')
@section('page_title', 'Pandit Management')
@section('content')

<!-- Status Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.pandits') }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ !request('status') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/30' : 'bg-white text-gray-600 border border-gray-200 hover:border-brand-500' }}">All <span class="ml-1 text-xs opacity-75">({{ $counts['all'] }})</span></a>
    <a href="{{ route('admin.pandits', ['status'=>'pending']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='pending' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/30' : 'bg-white text-gray-600 border border-gray-200 hover:border-yellow-500' }}">Pending <span class="ml-1 text-xs opacity-75">({{ $counts['pending'] }})</span></a>
    <a href="{{ route('admin.pandits', ['status'=>'approved']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='approved' ? 'bg-green-500 text-white shadow-lg shadow-green-500/30' : 'bg-white text-gray-600 border border-gray-200 hover:border-green-500' }}">Approved <span class="ml-1 text-xs opacity-75">({{ $counts['approved'] }})</span></a>
    <a href="{{ route('admin.pandits', ['status'=>'rejected']) }}" class="px-5 py-2 rounded-xl text-sm font-medium transition {{ request('status')==='rejected' ? 'bg-red-500 text-white shadow-lg shadow-red-500/30' : 'bg-white text-gray-600 border border-gray-200 hover:border-red-500' }}">Rejected <span class="ml-1 text-xs opacity-75">({{ $counts['rejected'] }})</span></a>
</div>

<!-- Search -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pandits by name or email..." class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-medium py-2.5 px-5 rounded-xl transition text-sm"><i class="fa-solid fa-search"></i></button>
    </form>
</div>

<!-- Pandits List -->
<div class="space-y-4">
    @forelse($pandits as $p)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if($p->profile_photo)
                        <img src="{{ asset('storage/' . $p->profile_photo) }}" class="w-16 h-16 rounded-xl object-cover border-2 border-gray-100">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-brand-100 flex items-center justify-center text-brand-500 text-2xl"><i class="fa-solid fa-user-tie"></i></div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $p->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $p->user->email }} · {{ $p->user->phone ?? '-' }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md font-medium">{{ $p->experience_years }} yrs</span>
                            <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-md font-medium">{{ $p->qualification ?? '-' }}</span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-medium">{{ $p->city }}, {{ $p->state }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $p->approval_status==='approved'?'bg-green-100 text-green-700':($p->approval_status==='pending'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') }}">{{ ucfirst($p->approval_status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('admin.review', $p) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-xl transition text-sm"><i class="fa-solid fa-eye mr-1"></i> Review</a>
                    @if($p->approval_status === 'pending')
                        <form method="POST" action="{{ route('admin.approve', $p) }}">@csrf<button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-xl transition text-sm"><i class="fa-solid fa-check mr-1"></i> Approve</button></form>
                    @endif
                    @if($p->approval_status === 'approved')
                        <form method="POST" action="{{ route('admin.suspend.pandit', $p) }}">@csrf<button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-xl transition text-sm" onclick="return confirm('Suspend this pandit?')"><i class="fa-solid fa-pause mr-1"></i> Suspend</button></form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100">
            <i class="fa-solid fa-user-tie text-5xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Pandits Found</h3>
            <p class="text-gray-500">{{ request('status') ? 'No '.request('status').' pandits' : 'No pandit registrations yet' }}.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">{{ $pandits->links() }}</div>
@endsection
