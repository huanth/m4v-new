@extends('layouts.app')

@section('title', 'Tin nhắn - M4V.ME')
@section('description', 'Nhắn tin với các thành viên khác trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tin nhắn</h1>
                    <p class="mt-2 text-gray-600">
                        Nhắn tin với các thành viên khác
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('profile') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Trang cá nhân
                    </a>
                    <a href="{{ url('/') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Trang chủ
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Conversations List -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Cuộc trò chuyện</h2>
                        
                        @if($conversations->count() > 0)
                            <div class="space-y-2">
                                @foreach($conversations as $userId => $messages)
                                    @php
                                        $otherUser = $messages->first()->sender_id == $user->id ? $messages->first()->receiver : $messages->first()->sender;
                                        $lastMessage = $messages->first();
                                        $unreadCount = $messages->where('receiver_id', $user->id)->where('is_read', false)->count();
                                    @endphp
                                    <a href="{{ route('chat.show', $otherUser->id) }}" 
                                       class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->route('userId') == $otherUser->id ? 'bg-blue-50 border border-blue-200' : '' }}">
                                        <div class="flex-shrink-0">
                                            @if($otherUser->avatar)
                                                <img src="{{ Storage::url($otherUser->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ strtoupper(substr($otherUser->username, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $otherUser->username }}
                                                </p>
                                                @if($unreadCount > 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ $lastMessage->message }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $lastMessage->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có cuộc trò chuyện</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Bắt đầu nhắn tin với ai đó để tạo cuộc trò chuyện.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg h-96">
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chọn cuộc trò chuyện</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Chọn một cuộc trò chuyện từ danh sách bên trái để bắt đầu nhắn tin.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = {{ $user->id }};

    // Initialize Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    // Subscribe to the channel for this user
    const channel = pusher.subscribe('private-chat.' + currentUserId);

    // Listen for new messages
    channel.bind('message.sent', function(data) {
        // Update unread count in header
        updateUnreadCount();
        // Reload page to show new conversation
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    });

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
});
</script>
@endsection
