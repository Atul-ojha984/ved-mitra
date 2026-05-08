<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\Booking;

class ChatController extends Controller
{
    public function show(Booking $booking)
    {
        $user = auth()->user();
        // Only booking customer or pandit can chat
        if ($booking->user_id !== $user->id && $booking->pandit->user_id !== $user->id) {
            abort(403);
        }
        if (!in_array($booking->status, ['confirmed', 'completed'])) {
            abort(403, 'Chat is available only for confirmed bookings.');
        }

        $booking->load('user', 'pandit.user', 'service');

        // Mark messages as read
        ChatMessage::where('booking_id', $booking->id)->where('receiver_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);

        $messages = ChatMessage::where('booking_id', $booking->id)->with('sender')->orderBy('created_at')->get();
        $otherUser = $booking->user_id === $user->id ? $booking->pandit->user : $booking->user;

        return view('chat.show', compact('booking', 'messages', 'otherUser'));
    }

    public function send(Request $request, Booking $booking)
    {
        $user = auth()->user();
        if ($booking->user_id !== $user->id && $booking->pandit->user_id !== $user->id) abort(403);

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $receiverId = $booking->user_id === $user->id ? $booking->pandit->user_id : $booking->user_id;

        ChatMessage::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);

        return back();
    }

    public function fetchMessages(Booking $booking)
    {
        $user = auth()->user();
        if ($booking->user_id !== $user->id && $booking->pandit->user_id !== $user->id) abort(403);

        ChatMessage::where('booking_id', $booking->id)->where('receiver_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);

        $messages = ChatMessage::where('booking_id', $booking->id)->with('sender')->orderBy('created_at')->get();
        return response()->json($messages);
    }
}
