<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chat interface
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->conversations();
        
        return view('chat.index', compact('user', 'conversations'));
    }

    /**
     * Show conversation with specific user
     */
    public function show($userId)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($userId);
        
        // Get conversation between current user and other user
        $messages = Message::between($user->id, $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return view('chat.conversation', compact('user', 'otherUser', 'messages'));
    }

    /**
     * Send a message
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Check if user is banned from messaging
        if (!$user->canComment()) {
            return response()->json(['error' => 'Bạn đã bị ban và không thể gửi tin nhắn.'], 403);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Load relationships
        $message->load(['sender', 'receiver']);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Get conversation messages
     */
    public function getMessages($userId)
    {
        $user = Auth::user();
        
        $messages = Message::between($user->id, $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json($messages);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($userId)
    {
        $user = Auth::user();
        
        Message::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get unread messages count
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $user->getUnreadMessagesCount();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get count of users with unread messages
     */
    public function getUnreadConversationsCount()
    {
        $user = Auth::user();
        $count = $user->getUnreadConversationsCount();
        
        return response()->json(['count' => $count]);
    }
}
