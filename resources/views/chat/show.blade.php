<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat - Booking #{{ $booking->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:500,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-[Inter] antialiased h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100 px-6 py-3 flex items-center gap-4 shrink-0">
        <a href="{{ auth()->user()->role === 'pandit' ? route('pandit.bookings') : route('user.dashboard') }}" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-arrow-left text-lg"></i></a>
        <img src="{{ $otherUser->avatar_url }}" class="w-10 h-10 rounded-full border-2 border-orange-200">
        <div>
            <h2 class="font-bold text-gray-900 text-sm">{{ $otherUser->name }}</h2>
            <p class="text-xs text-gray-500">{{ $booking->service->name ?? 'Puja' }} · {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
        </div>
        <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">{{ ucfirst($booking->status) }}</span>
    </header>

    <!-- Messages -->
    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4">
        @foreach($messages as $msg)
            @php $isMe = $msg->sender_id === auth()->id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%]">
                    @if(!$isMe)<p class="text-xs text-gray-400 mb-1 ml-1">{{ $msg->sender->name }}</p>@endif
                    <div class="{{ $isMe ? 'bg-orange-500 text-white rounded-2xl rounded-br-md' : 'bg-white text-gray-900 rounded-2xl rounded-bl-md shadow-sm border border-gray-100' }} px-4 py-3">
                        <p class="text-sm">{{ $msg->message }}</p>
                    </div>
                    <div class="flex items-center gap-1 mt-1 {{ $isMe ? 'justify-end' : '' }}">
                        <p class="text-[10px] text-gray-400">{{ $msg->created_at->format('h:i A') }}</p>
                        @if($isMe && $msg->read_at)<i class="fa-solid fa-check-double text-[10px] text-blue-400"></i>@elseif($isMe)<i class="fa-solid fa-check text-[10px] text-gray-300"></i>@endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input -->
    <div class="bg-white border-t border-gray-100 p-4 shrink-0">
        <form method="POST" action="{{ route('chat.send', $booking) }}" class="flex gap-3">
            @csrf
            <input type="text" name="message" required placeholder="Type your message..." autofocus class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none text-sm">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl transition font-medium"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <script>
        // Auto-scroll to bottom
        const chatDiv = document.getElementById('chat-messages');
        chatDiv.scrollTop = chatDiv.scrollHeight;

        // Poll for new messages every 5 seconds
        setInterval(async () => {
            try {
                const resp = await fetch('{{ route("chat.messages", $booking) }}');
                const msgs = await resp.json();
                // Simple reload if new messages
                if (msgs.length > {{ $messages->count() }}) location.reload();
            } catch (e) {}
        }, 5000);
    </script>
</body></html>
