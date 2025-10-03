@extends('layouts.app')

@section('title', 'Tin nhắn với ' . $otherUser->username . ' - M4V.ME')
@section('description', 'Cuộc trò chuyện với ' . $otherUser->username . ' trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('chat.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                    <div class="flex items-center space-x-3">
                        @if($otherUser->avatar)
                            <img src="{{ Storage::url($otherUser->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">
                                    {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">{{ $otherUser->username }}</h1>
                            <p class="text-sm text-gray-500">{{ $otherUser->getRoleDisplayName() }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('user.profile', $otherUser) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Xem Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white shadow rounded-lg h-96 flex flex-col">
            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id == $user->id ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-end space-x-2 {{ $message->sender_id == $user->id ? 'flex-row-reverse space-x-reverse' : '' }}">
                                @if($message->sender_id != $user->id)
                                    <div class="flex-shrink-0">
                                        @if($message->sender->avatar)
                                            <img src="{{ Storage::url($message->sender->avatar) }}" alt="Avatar" class="h-6 w-6 rounded-full object-cover">
                                        @else
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-xs font-bold text-white">
                                                    {{ strtoupper(substr($message->sender->username, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="px-4 py-2 rounded-lg {{ $message->sender_id == $user->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <p class="text-xs mt-1 {{ $message->sender_id == $user->id ? 'text-blue-100' : 'text-gray-500' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-200 p-4">
                <form id="message-form" class="flex space-x-2">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                    <input type="text" 
                           id="message-input"
                           name="message" 
                           placeholder="Nhập tin nhắn..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           maxlength="1000"
                           required>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const receiverId = {{ $otherUser->id }};
    const currentUserId = {{ $user->id }};

    // Initialize Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    // Subscribe to the channel for this conversation
    const channel = pusher.subscribe('private-chat.' + currentUserId);

    // Listen for new messages
    channel.bind('message.sent', function(data) {
        if (data.message.sender_id == receiverId) {
            addMessageToChat(data.message);
            scrollToBottom();
        }
    });

    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable form while sending
        const submitBtn = messageForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessageToChat(data.message);
                messageInput.value = '';
                scrollToBottom();
                // Update unread count after sending message
                updateUnreadCount();
            } else {
                alert('Có lỗi xảy ra khi gửi tin nhắn: ' + (data.error || 'Lỗi không xác định'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi gửi tin nhắn');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
        });
    });

    // Add message to chat
    function addMessageToChat(message) {
        const isOwnMessage = message.sender_id == currentUserId;
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
        
        const time = new Date(message.created_at).toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });

        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="flex items-end space-x-2 ${isOwnMessage ? 'flex-row-reverse space-x-reverse' : ''}">
                    ${!isOwnMessage ? `
                        <div class="flex-shrink-0">
                            <div class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-xs font-bold text-white">
                                    ${message.sender.username.charAt(0).toUpperCase()}
                                </span>
                            </div>
                        </div>
                    ` : ''}
                    <div class="px-4 py-2 rounded-lg ${isOwnMessage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'}">
                        <p class="text-sm">${message.message}</p>
                        <p class="text-xs mt-1 ${isOwnMessage ? 'text-blue-100' : 'text-gray-500'}">
                            ${time}
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    }

    // Scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initial scroll to bottom
    scrollToBottom();

    // Mark messages as read when entering conversation
    markMessagesAsRead();

    // Function to mark messages as read
    function markMessagesAsRead() {
        fetch(`/chat/${receiverId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update unread count in header
                updateUnreadCount();
            }
        })
        .catch(error => {
            console.error('Error marking messages as read:', error);
        });
    }

    // Function to update unread count in header
    function updateUnreadCount() {
        fetch('/chat/unread/conversations-count', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const chatLink = document.querySelector('a[href="/chat"]');
            if (chatLink) {
                const countSpan = chatLink.querySelector('span');
                if (data.count > 0) {
                    if (!countSpan) {
                        const newSpan = document.createElement('span');
                        newSpan.className = 'text-[#FF0000]';
                        newSpan.textContent = `(${data.count})`;
                        chatLink.appendChild(newSpan);
                    } else {
                        countSpan.textContent = `(${data.count})`;
                    }
                } else {
                    if (countSpan) {
                        countSpan.remove();
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error updating unread count:', error);
        });
    }

    // Update unread count when receiving new messages
    channel.bind('message.sent', function(data) {
        if (data.message.sender_id == receiverId) {
            addMessageToChat(data.message);
            scrollToBottom();
            // Don't update unread count here as the message is not read yet
        }
    });
});
</script>
@endsection
