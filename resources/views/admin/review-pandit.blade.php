@extends('admin.layout')
@section('title', 'Review ' . $pandit->user->name)
@section('page_title', 'Review Application')
@section('content')

<div class="mb-6"><a href="{{ route('admin.pandits') }}" class="text-brand-600 font-medium hover:underline text-sm"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Pandits</a></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Card -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-brand-500 to-amber-500 h-20"></div>
            <div class="px-6 pb-6 -mt-10 text-center">
                @if($pandit->profile_photo)
                    <img src="{{ asset('storage/' . $pandit->profile_photo) }}" class="w-20 h-20 rounded-full border-4 border-white shadow-lg mx-auto object-cover">
                @else
                    <div class="w-20 h-20 rounded-full bg-brand-100 border-4 border-white shadow-lg mx-auto flex items-center justify-center text-brand-500 text-3xl"><i class="fa-solid fa-user-tie"></i></div>
                @endif
                <h2 class="text-xl font-bold text-gray-900 mt-3">{{ $pandit->user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $pandit->user->email }}</p>
                <p class="text-gray-400 text-sm mt-1"><i class="fa-solid fa-phone text-xs mr-1"></i> {{ $pandit->user->phone }}</p>
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $pandit->approval_status==='approved'?'bg-green-100 text-green-700':($pandit->approval_status==='pending'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') }}">{{ ucfirst($pandit->approval_status) }}</span>
                </div>
            </div>
        </div>
        <!-- Documents -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Documents</h3>
            <div class="space-y-3">
                @foreach(['aadhaar_document' => ['Aadhaar Card', 'fa-id-card'], 'pan_document' => ['PAN Card', 'fa-file-lines'], 'certificate_document' => ['Certificate', 'fa-award']] as $field => [$label, $icon])
                    @if($pandit->$field)
                        <a href="{{ asset('storage/' . $pandit->$field) }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-brand-50 transition text-sm group">
                            <i class="fa-solid {{ $icon }} text-brand-500 w-5"></i><span class="text-gray-700 group-hover:text-brand-600">{{ $label }}</span><i class="fa-solid fa-external-link text-xs text-gray-400 ml-auto"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Personal</h3>
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div><p class="text-gray-400">Gender</p><p class="font-semibold text-gray-900">{{ ucfirst($pandit->gender ?? '-') }}</p></div>
                <div><p class="text-gray-400">Date of Birth</p><p class="font-semibold text-gray-900">{{ $pandit->date_of_birth ? $pandit->date_of_birth->format('d M Y') : '-' }}</p></div>
                <div><p class="text-gray-400">Languages</p><p class="font-semibold text-gray-900">{{ $pandit->languages ?? '-' }}</p></div>
                <div><p class="text-gray-400">Available Timings</p><p class="font-semibold text-gray-900">{{ $pandit->available_timings ?? '-' }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Professional</h3>
            <div class="grid grid-cols-2 gap-y-4 text-sm mb-4">
                <div><p class="text-gray-400">Experience</p><p class="font-semibold text-gray-900">{{ $pandit->experience_years }} years</p></div>
                <div><p class="text-gray-400">Qualification</p><p class="font-semibold text-gray-900">{{ $pandit->qualification ?? '-' }}</p></div>
                <div><p class="text-gray-400">Specialization</p><p class="font-semibold text-gray-900">{{ $pandit->specialization ?? '-' }}</p></div>
                <div><p class="text-gray-400">Consultation Fee</p><p class="font-semibold text-gray-900">₹{{ number_format($pandit->consultation_fee ?? 0) }}</p></div>
            </div>
            <div class="mb-4"><p class="text-gray-400 text-sm mb-1">Bio</p><p class="text-gray-700 text-sm bg-gray-50 p-4 rounded-xl">{{ $pandit->bio }}</p></div>
            <div><p class="text-gray-400 text-sm mb-2">Services</p>
                <div class="flex flex-wrap gap-2">@foreach($pandit->services as $s)<span class="text-xs font-medium bg-brand-50 text-brand-700 px-3 py-1 rounded-lg">{{ $s->name }}</span>@endforeach</div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Location</h3>
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div class="col-span-2"><p class="text-gray-400">Address</p><p class="font-semibold text-gray-900">{{ $pandit->address }}</p></div>
                <div><p class="text-gray-400">City</p><p class="font-semibold text-gray-900">{{ $pandit->city }}</p></div>
                <div><p class="text-gray-400">State</p><p class="font-semibold text-gray-900">{{ $pandit->state }}</p></div>
                <div><p class="text-gray-400">Pincode</p><p class="font-semibold text-gray-900">{{ $pandit->pincode }}</p></div>
            </div>
        </div>

        <!-- Action -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ showReject: false }">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Decision</h3>
            <div class="flex gap-4 mb-4">
                @if($pandit->approval_status !== 'approved')
                <form method="POST" action="{{ route('admin.approve', $pandit) }}" class="flex-1">@csrf<button class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-green-500/30 flex items-center justify-center gap-2"><i class="fa-solid fa-check-double"></i> Approve</button></form>
                @endif
                @if($pandit->approval_status !== 'rejected')
                <button @click="showReject=!showReject" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-red-500/30 flex items-center justify-center gap-2"><i class="fa-solid fa-xmark"></i> Reject</button>
                @endif
            </div>
            <div x-show="showReject" x-transition class="bg-red-50 border border-red-200 rounded-xl p-4">
                <form method="POST" action="{{ route('admin.reject', $pandit) }}">@csrf
                    <label class="block text-sm font-medium text-red-700 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" rows="3" class="w-full rounded-xl border border-red-200 bg-white px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 outline-none"></textarea>
                    <button class="mt-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm">Confirm Rejection</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
