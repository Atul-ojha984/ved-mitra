@extends('pandit.layout')
@section('title', 'Reviews')
@section('page_title', 'Reviews & Ratings')
@section('content')

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <p class="text-5xl font-bold text-brand-600 mb-1">{{ number_format($avgRating, 1) }}</p>
        <div class="flex items-center justify-center gap-1 mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="fa-solid fa-star {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' }} text-lg"></i>
            @endfor
        </div>
        <p class="text-gray-500 text-sm">{{ $totalReviews }} reviews</p>
    </div>
    <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Rating Breakdown</h3>
        @foreach($ratingBreakdown as $stars => $count)
            <div class="flex items-center gap-3 mb-2">
                <span class="text-sm font-medium text-gray-700 w-6 text-right">{{ $stars }}</span>
                <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500 rounded-full transition-all duration-500" style="width: {{ $totalReviews > 0 ? ($count / $totalReviews * 100) : 0 }}%"></div>
                </div>
                <span class="text-sm text-gray-400 w-8">{{ $count }}</span>
            </div>
        @endforeach
    </div>
</div>

<!-- Reviews List -->
<div class="space-y-4">
    @forelse($reviews as $r)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $r->user->avatar_url }}" class="w-12 h-12 rounded-full border-2 border-gray-100">
                    <div>
                        <p class="font-bold text-gray-900">{{ $r->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $r->booking->service->name ?? 'Puja' }} · {{ $r->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $r->rating ? 'text-yellow-400' : 'text-gray-200' }} text-sm"></i>
                    @endfor
                </div>
            </div>
            @if($r->comment)<p class="text-gray-600 text-sm mt-3 leading-relaxed">{{ $r->comment }}</p>@endif
        </div>
    @empty
        <div class="bg-white rounded-2xl p-16 text-center shadow-sm border border-gray-100"><i class="fa-solid fa-star text-5xl text-gray-200 mb-4"></i><h3 class="text-xl font-bold text-gray-900 mb-2">No Reviews Yet</h3><p class="text-gray-500">Reviews from customers will appear here.</p></div>
    @endforelse
</div>
<div class="mt-6">{{ $reviews->links() }}</div>
@endsection
