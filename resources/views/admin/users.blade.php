@extends('admin.layout')
@section('title', 'User Management')
@section('page_title', 'User Management')
@section('content')

<!-- Search & Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-gray-500 font-medium mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, or phone..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition">
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-medium mb-1">Role</label>
            <select name="role" class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">All Roles</option>
                <option value="user" {{ request('role')==='user'?'selected':'' }}>Customer</option>
                <option value="pandit" {{ request('role')==='pandit'?'selected':'' }}>Pandit</option>
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 font-medium mb-1">Status</label>
            <select name="status" class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">All</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                <option value="suspended" {{ request('status')==='suspended'?'selected':'' }}>Suspended</option>
                <option value="banned" {{ request('status')==='banned'?'selected':'' }}>Banned</option>
            </select>
        </div>
        <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-medium py-2.5 px-6 rounded-xl transition text-sm"><i class="fa-solid fa-search mr-1"></i> Filter</button>
        <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-gray-600 text-sm font-medium py-2.5 px-3">Clear</a>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="text-xs uppercase text-gray-500 bg-gray-50/50"><th class="px-6 py-3 font-semibold border-b">User</th><th class="px-6 py-3 font-semibold border-b">Role</th><th class="px-6 py-3 font-semibold border-b">Phone</th><th class="px-6 py-3 font-semibold border-b">Status</th><th class="px-6 py-3 font-semibold border-b">Joined</th><th class="px-6 py-3 font-semibold border-b">Actions</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50 transition text-sm" x-data="{ open: false }">
                    <td class="px-6 py-3"><div class="flex items-center gap-3"><img src="{{ $u->avatar_url }}" class="w-9 h-9 rounded-full"><div><p class="font-medium text-gray-900">{{ $u->name }}</p><p class="text-xs text-gray-400">{{ $u->email }}</p></div></div></td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $u->role==='pandit'?'bg-purple-100 text-purple-700':'bg-blue-100 text-blue-700' }}">{{ ucfirst($u->role) }}</span></td>
                    <td class="px-6 py-3 text-gray-600">{{ $u->phone ?? '-' }}</td>
                    <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $u->account_status==='active'?'bg-green-100 text-green-700':($u->account_status==='suspended'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') }}">{{ ucfirst($u->account_status) }}</span></td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $u->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <div class="relative" x-data="{ menu: false }">
                            <button @click="menu=!menu" class="text-gray-400 hover:text-gray-700 p-1"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <div x-show="menu" @click.outside="menu=false" x-transition class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 z-20 py-1">
                                @if($u->account_status === 'active')
                                    <form method="POST" action="{{ route('admin.user.status', $u) }}">@csrf<input type="hidden" name="status" value="suspended"><button class="block w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50"><i class="fa-solid fa-pause mr-2"></i>Suspend</button></form>
                                    <form method="POST" action="{{ route('admin.user.status', $u) }}">@csrf<input type="hidden" name="status" value="banned"><button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-solid fa-ban mr-2"></i>Ban</button></form>
                                @else
                                    <form method="POST" action="{{ route('admin.user.status', $u) }}">@csrf<input type="hidden" name="status" value="active"><button class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50"><i class="fa-solid fa-circle-check mr-2"></i>Activate</button></form>
                                @endif
                                <form method="POST" action="{{ route('admin.user.delete', $u) }}" onsubmit="return confirm('Delete {{ $u->name }}? This cannot be undone.')">@csrf @method('DELETE')<button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 border-t border-gray-100"><i class="fa-solid fa-trash mr-2"></i>Delete</button></form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
