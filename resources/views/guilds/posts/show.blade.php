@extends('layouts.app')

@section('title', $post->title . ' - ' . $guild->name)
@section('description', 'Xem bài viết: ' . $post->title . ' trong bang hội ' . $guild->name)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="">

        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$userMembership" />

        <!-- Guild Banner & Announcement -->
        <x-guild-banner-announcement :guild="$guild" />

        <!-- Post Card -->
        <div class="bg-white rounded-lg overflow-hidden">

            <!-- Post Header -->
            <div class="py-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            @if($post->is_pinned)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Ghim
                                </span>
                            @endif
                            @if($post->is_locked)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Khóa
                                </span>
                            @endif
                            @if($post->category)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 py-3 bg-gradient-to-r from-green-50 to-indigo-50 border border-blue-200 rounded-lg">
            <!-- Post Meta -->
            <div class="py-3">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            @if($post->author->avatar)
                                <img src="{{ Storage::url($post->author->avatar) }}" alt="Avatar" class="h-6 w-6 rounded-full object-cover">
                            @else
                                <div class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">
                                        {{ strtoupper(substr($post->author->username, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <span class="font-medium">{{ $post->author->username }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <div class="py-6">
                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        </div>

        <!-- Post Actions -->
        <div class="py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Like Button -->
                    <form method="POST" action="{{ route('guilds.posts.like', [$guild->id, $post->id]) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $post->isLikedBy(auth()->id()) ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-gray-600 hover:text-red-600 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>{{ $post->likes_count }}</span>
                        </button>
                    </form>

                    <!-- Comments Count -->
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>{{ $post->comments_count }}</span>
                    </div>

                    <!-- Views Count -->
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ $post->views_count }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-3">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center space-x-4">
                    @if($post->updated_at != $post->created_at)
                        <span class="text-gray-500">Đã chỉnh sửa {{ $post->updated_at->format('d/m/Y H:i') }}</span>
                    @else
                        <span class="text-gray-500">Đã tạo {{ $post->created_at->format('d/m/Y H:i') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Post Actions -->
        @if($userMembership)
            <div class="flex items-center space-x-2">
                @if($post->canEdit(auth()->id()))
                    <a href="{{ route('guilds.posts.edit', [$guild->id, $post->id]) }}" 
                        class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Sửa
                    </a>
                @endif
                
                @if($post->canDelete(auth()->id()))
                    <form method="POST" action="{{ route('guilds.posts.delete', [$guild->id, $post->id]) }}" 
                            onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Xóa
                        </button>
                    </form>
                @endif
                
                @if($post->canPin(auth()->id()))
                    <form method="POST" action="{{ route('guilds.posts.pin', [$guild->id, $post->id]) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1 border border-yellow-300 rounded-md text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-50">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            {{ $post->is_pinned ? 'Bỏ ghim' : 'Ghim' }}
                        </button>
                    </form>
                @endif
                
                @if($post->canLock(auth()->id()))
                    <form method="POST" action="{{ route('guilds.posts.lock', [$guild->id, $post->id]) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $post->is_locked ? 'Mở khóa' : 'Khóa' }}
                        </button>
                    </form>
                @endif
            </div>
        @endif

        <!-- Comments Section -->
        <div class="mt-8">
            <div class="bg-white">
                <!-- Add Comment Form -->
                <div class="py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bình luận</h3>
                    <form method="POST" action="{{ route('guilds.posts.comments.store', [$guild->id, $post->id]) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <textarea name="content" rows="3" required 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 focus:ring-red-500 @enderror" 
                                          placeholder="Viết bình luận của bạn..."></textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Bình luận
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="px-6 py-4">
                    @if($post->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    @if($comment->user->avatar)
                                        <img src="{{ Storage::url($comment->user->avatar) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-xs font-bold text-white">
                                                {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900">{{ $comment->user->username }}</p>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <!-- Like Comment Button -->
                                                <form method="POST" action="{{ route('guilds.posts.comments.like', [$guild->id, $post->id, $comment->id]) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="flex items-center space-x-1 text-xs text-gray-500 hover:text-red-600 transition-colors">
                                                        <svg class="w-4 h-4 {{ $comment->isLikedBy(auth()->id()) ? 'fill-current text-red-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span>{{ $comment->likes_count }}</span>
                                                    </button>
                                                </form>

                                                <!-- Comment Actions -->
                                                @if($comment->canEdit(auth()->id()) || $comment->canDelete(auth()->id()))
                                                <div class="flex items-center space-x-1">
                                                    @if($comment->canEdit(auth()->id()))
                                                        <button onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')" 
                                                                class="text-xs text-blue-600 hover:text-blue-800">
                                                            Sửa
                                                        </button>
                                                    @endif
                                                    @if($comment->canDelete(auth()->id()))
                                                        <form method="POST" action="{{ route('guilds.posts.comments.delete', [$guild->id, $post->id, $comment->id]) }}" 
                                                              onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800">
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700" id="comment-content-{{ $comment->id }}">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bình luận nào</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Hãy là người đầu tiên bình luận về bài viết này.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Comment Modal -->
<div id="editCommentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Chỉnh sửa bình luận</h3>
            <form id="editCommentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <textarea id="editCommentContent" name="content" rows="3" required 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                            Cập nhật
                        </button>
                        <button type="button" onclick="closeEditCommentModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                            Hủy
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editComment(commentId, content) {
    document.getElementById('editCommentContent').value = content;
    document.getElementById('editCommentForm').action = '{{ route("guilds.posts.comments.update", [$guild->id, $post->id, ":commentId"]) }}'.replace(':commentId', commentId);
    document.getElementById('editCommentModal').classList.remove('hidden');
}

function closeEditCommentModal() {
    document.getElementById('editCommentModal').classList.add('hidden');
}
</script>
@endsection
