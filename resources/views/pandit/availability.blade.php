@extends('pandit.layout')
@section('title', 'Availability')
@section('page_title', 'Manage Availability')
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Weekly Schedule -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-5"><i class="fa-solid fa-clock text-brand-500 mr-2"></i> Weekly Schedule</h3>
        <form method="POST" action="{{ route('pandit.availability.save') }}">
            @csrf
            <div class="space-y-3">
                @foreach(\App\Models\Availability::DAYS as $i => $day)
                    @php $existing = $slots->firstWhere('day_of_week', $i); @endphp
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100" x-data="{ active: {{ $existing ? 'true' : 'false' }} }">
                        <input type="hidden" name="slots[{{ $i }}][day]" value="{{ $i }}">
                        <label class="flex items-center gap-2 w-28 shrink-0 cursor-pointer">
                            <input type="checkbox" name="slots[{{ $i }}][active]" value="1" x-model="active" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                            <span class="font-medium text-sm text-gray-800">{{ $day }}</span>
                        </label>
                        <div class="flex items-center gap-2 flex-1" x-show="active" x-transition>
                            <input type="time" name="slots[{{ $i }}][start]" value="{{ $existing ? \Carbon\Carbon::parse($existing->start_time)->format('H:i') : '09:00' }}" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                            <span class="text-gray-400 text-sm">to</span>
                            <input type="time" name="slots[{{ $i }}][end]" value="{{ $existing ? \Carbon\Carbon::parse($existing->end_time)->format('H:i') : '18:00' }}" class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        </div>
                        <span x-show="!active" class="text-sm text-gray-400 italic">Unavailable</span>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="mt-6 w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-brand-500/30 transition flex items-center justify-center gap-2"><i class="fa-solid fa-save"></i> Save Schedule</button>
        </form>
    </div>

    <!-- Block Dates -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4"><i class="fa-solid fa-ban text-red-500 mr-2"></i> Block Date</h3>
            <form method="POST" action="{{ route('pandit.availability.block') }}">
                @csrf
                <div class="space-y-3">
                    <input type="date" name="blocked_date" required min="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    <input type="text" name="reason" placeholder="Reason (optional)" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl transition text-sm"><i class="fa-solid fa-ban mr-1"></i> Block Date</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Blocked Dates</h3>
            @forelse($blockedDates as $bd)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div><p class="text-sm font-medium text-gray-900">{{ $bd->blocked_date->format('d M Y') }}</p><p class="text-xs text-gray-400">{{ $bd->reason ?? 'No reason' }}</p></div>
                    <form method="POST" action="{{ route('pandit.availability.unblock', $bd) }}">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-600 text-xs"><i class="fa-solid fa-trash"></i></button></form>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">No blocked dates</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
