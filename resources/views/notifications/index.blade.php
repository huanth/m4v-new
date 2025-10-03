@extends('layouts.app')

@section('title', 'Thông báo')
@section('description', 'Xem tất cả thông báo của bạn')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Thông báo</h1>
                    <p class="text-gray-600 mt-1">Quản lý tất cả thông báo của bạn</p>
                </div>
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Đánh dấu tất cả đã đọc
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white shadow rounded-lg">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <div class="p-6 hover:bg-gray-50 transition-colors {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                            <div class="flex items-start space-x-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($notification->fromUser->avatar)
                                        <img src="{{ Storage::url($notification->fromUser->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-sm font-bold text-white">
                                                {{ strtoupper(substr($notification->fromUser->username, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">
                                                <span class="font-medium">{{ $notification->fromUser->username }}</span>
                                                {{ $notification->message }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center space-x-2">
                                            @if(!$notification->is_read)
                                                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                        Đánh dấu đã đọc
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <!-- Link to related content -->
                                            @if($notification->type === 'post_like' || $notification->type === 'post_comment')
                                                <a href="#" 
                                                   class="text-xs text-gray-500 hover:text-gray-700"
                                                   onclick="alert('Tính năng này sẽ được cập nhật sớm')">
                                                    Xem bài viết
                                                </a>
                                            @elseif($notification->type === 'comment_like')
                                                <a href="#" 
                                                   class="text-xs text-gray-500 hover:text-gray-700"
                                                   onclick="alert('Tính năng này sẽ được cập nhật sớm')">
                                                    Xem bình luận
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Unread indicator -->
                                @if(!$notification->is_read)
                                    <div class="flex-shrink-0">
                                        <div class="h-2 w-2 bg-blue-600 rounded-full"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM15 3h5l-5-5v5z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có thông báo nào</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Bạn sẽ nhận được thông báo khi có người thích hoặc bình luận bài viết của bạn.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection