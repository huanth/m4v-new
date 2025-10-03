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
                        <span>{{ $post->getTotalCommentsCount() }}</span>
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
                    @if($comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($comments as $comment)
                            <div class="flex space-x-3">
                                    <!-- Avatar -->
                                <div class="flex-shrink-0 w-1/12 flex flex-col items-center">
                                    @if($comment->user->avatar)
                                            <img src="{{ Storage::url($comment->user->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover box-shadow">
                                    @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center box-shadow">
                                                <span class="text-sm font-bold text-white">
                                                {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                        @php
                                            $role = $comment->user->role;
                                            $roleColor = match($role) {
                                                'SADMIN' => 'text-red-700',
                                                'ADMIN' => 'text-orange-600',
                                                'SMod' => 'text-green-600',
                                                'FMod' => 'text-blue-600',
                                                default => 'text-gray-900',
                                            };
                                        @endphp
                                        <p class="text-sm text-center font-semibold {{ $roleColor }}" style="word-break: break-word;">
                                            {{ $comment->user->username }}
                                        </p>

                                        <!-- Role in guild -->
                                        @php
                                            $guildMember = $guild->members()->where('user_id', $comment->user->id)->first();
                                            $guildRole = $guildMember ? $guildMember->role : null;
                                            $guildRoleColor = match($guildRole) {
                                                'leader' => 'text-red-700',
                                                'vice_leader' => 'text-orange-600',
                                                'elder' => 'text-green-600',
                                                'member' => 'text-gray-700',
                                                default => 'text-gray-400',
                                            };
                                        @endphp
                                        @if($guildMember && $guildMember->role !== 'member')
                                            <p class="text-xs text-center {{ $guildRoleColor }}">[{{ $guildMember->role_display_name }}]</p>
                                        @endif
                                </div>

                                    <!-- Comment Content -->
                                <div class="flex-1 min-w-0 w-5/12">
                                        <div class="bg-white min-h-[150px] h-auto border border-gray-200 rounded-xl px-4 py-2 shadow-sm hover:shadow-md transition-shadow">
                                            <!-- Comment Header -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center space-x-3">
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                                    @if($comment->created_at != $comment->updated_at)
                                                        <span class="text-xs text-gray-400">(đã chỉnh sửa)</span>
                                                    @endif
                                                    @if($comment->parent_id)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            Trả lời {{ $comment->parent->user->username }}
                                                        </span>
                                                    @endif
                                            </div>
                                                
                                                <!-- Comment Actions -->
                                            <div class="flex items-center space-x-2">
                                                <!-- Like Comment Button -->
                                                <form method="POST" action="{{ route('guilds.posts.comments.like', [$guild->id, $post->id, $comment->id]) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                                class="flex items-center space-x-1 px-2 py-1 rounded-md text-xs font-medium transition-colors {{ $comment->isLikedBy(auth()->id()) ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-gray-500 hover:text-red-600 hover:bg-gray-100' }}">
                                                            <svg class="w-4 h-4 {{ $comment->isLikedBy(auth()->id()) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        </svg>
                                                        <span>{{ $comment->likes_count }}</span>
                                                    </button>
                                                </form>

                                                    <!-- Reply Button -->
                                                    @if($userMembership)
                                                        <button onclick="toggleReplyForm({{ $comment->id }})" 
                                                                class="px-2 py-1 rounded-md text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition-colors">
                                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                            Trả lời
                                                        </button>
                                                    @endif

                                                    <!-- Edit/Delete Actions -->
                                                    @if($comment->canEdit(auth()->id()) || $comment->canDelete(auth()->id()))
                                                    <div class="flex items-center space-x-1">
                                                        @if($comment->canEdit(auth()->id()))
                                                            <button onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')" 
                                                                    class="px-2 py-1 rounded-md text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 transition-colors">
                                                                Sửa
                                                            </button>
                                                        @endif
                                                        @if($comment->canDelete(auth()->id()))
                                                            @php
                                                                $canDeleteReason = '';
                                                                if ($comment->user_id == auth()->id()) {
                                                                    $canDeleteReason = 'Tác giả';
                                                                } elseif (in_array(auth()->user()->role, ['SADMIN', 'ADMIN'])) {
                                                                    $canDeleteReason = auth()->user()->role;
                                                                } else {
                                                                    $guildMember = $guild->members()->where('user_id', auth()->id())->first();
                                                                    if ($guildMember && in_array($guildMember->role, ['leader', 'vice_leader', 'elder'])) {
                                                                        $canDeleteReason = $guildMember->role_display_name;
                                                                    }
                                                                }
                                                            @endphp
                                                            <form method="POST" action="{{ route('guilds.posts.comments.delete', [$guild->id, $post->id, $comment->id]) }}" 
                                                                  onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này? (Quyền: {{ $canDeleteReason }})')" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-2 py-1 rounded-md text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 transition-colors" title="Xóa bình luận (Quyền: {{ $canDeleteReason }})">
                                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                    @endif

                                                <!-- Count comments -->
                                                <div class="text-xs text-gray-500">
                                                    # {{ $comment->id }}
                                                </div>
                                            </div>
                                        </div>

                                            <!-- Comment Content -->
                                            <div class="mb-3">
                                                @if($comment->quoted_content)
                                                    <div class="bg-gray-50 border-l-4 border-blue-400 pl-3 py-2 mb-3 rounded-r-md">
                                                        <p class="text-xs text-gray-600 mb-1">
                                                            <span class="font-medium">{{ $comment->parent->user->username }}</span> đã viết:
                                                        </p>
                                                        <p class="text-sm text-gray-700 italic">{{ Str::limit($comment->quoted_content, 150) }}</p>
                                                    </div>
                                                @endif
                                                <p class="text-sm text-gray-800 leading-relaxed" id="comment-content-{{ $comment->id }}">{{ $comment->content }}</p>
                                            </div>
                                        </div>

                                        <!-- Reply Form (Hidden by default) -->
                                        @if($userMembership)
                                            <div id="reply-form-{{ $comment->id }}" class="hidden mt-3 ml-4">
                                                <form method="POST" action="{{ route('guilds.posts.comments.store', [$guild->id, $post->id]) }}" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                    @csrf
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <div class="space-y-3">
                                                        <!-- Quote Option (only show if comment doesn't have quoted_content) -->
                                                        @if(!$comment->quoted_content)
                                                            <div class="flex items-center space-x-2">
                                                                <input type="checkbox" id="quote-{{ $comment->id }}" name="include_quote" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onchange="toggleQuoteContent({{ $comment->id }})">
                                                                <label for="quote-{{ $comment->id }}" class="text-sm text-gray-700">Trích dẫn bình luận này</label>
                                                            </div>
                                                            
                                                            <!-- Quote Content (Hidden by default) -->
                                                            <div id="quote-content-{{ $comment->id }}" class="hidden">
                                                                <div class="bg-white border border-gray-300 rounded-md p-3">
                                                                    <p class="text-xs text-gray-600 mb-2">Nội dung trích dẫn:</p>
                                                                    <div class="bg-gray-50 border-l-4 border-blue-400 pl-3 py-2 rounded-r-md">
                                                                        <p class="text-sm text-gray-700 italic">{{ Str::limit($comment->content, 200) }}</p>
                                                                    </div>
                                                                    <input type="hidden" name="quoted_content" value="{{ Str::limit($comment->content, 200) }}">
                                                                </div>
                                                            </div>
                                                        @else
                                                            <!-- Show message that quoting is not allowed -->
                                                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                                                <div class="flex items-center">
                                                                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                    </svg>
                                                                    <p class="text-sm text-yellow-800">Không thể trích dẫn bình luận đã có trích dẫn</p>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <!-- Reply Content -->
                                                        <div>
                                                            <textarea name="content" rows="3" required 
                                                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 focus:ring-red-500 @enderror" 
                                                                      placeholder="Trả lời {{ $comment->user->username }}..."></textarea>
                                                            @error('content')
                                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" onclick="toggleReplyForm({{ $comment->id }})" 
                                                                    class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Hủy
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Trả lời
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Comments Pagination -->
                        @if($comments->hasPages())
                            <div class="mt-6">
                                {{ $comments->links('vendor.pagination.tailwind') }}
                            </div>
                        @endif
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

function toggleReplyForm(commentId) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    if (replyForm.classList.contains('hidden')) {
        replyForm.classList.remove('hidden');
        // Focus on textarea
        const textarea = replyForm.querySelector('textarea[name="content"]');
        if (textarea) {
            textarea.focus();
        }
    } else {
        replyForm.classList.add('hidden');
        // Reset quote checkbox and hide quote content
        const quoteCheckbox = document.getElementById('quote-' + commentId);
        const quoteContent = document.getElementById('quote-content-' + commentId);
        if (quoteCheckbox) quoteCheckbox.checked = false;
        if (quoteContent) quoteContent.classList.add('hidden');
    }
}

function toggleQuoteContent(commentId) {
    const quoteCheckbox = document.getElementById('quote-' + commentId);
    const quoteContent = document.getElementById('quote-content-' + commentId);
    
    if (quoteCheckbox.checked) {
        quoteContent.classList.remove('hidden');
    } else {
        quoteContent.classList.add('hidden');
    }
}

function quoteComment(commentId) {
    // Check if quote checkbox exists (it won't exist if comment already has quoted_content)
    const quoteCheckbox = document.getElementById('quote-' + commentId);
    if (!quoteCheckbox) {
        // If no quote checkbox, just open reply form without quoting
        toggleReplyForm(commentId);
        return;
    }
    
    // Open reply form
    toggleReplyForm(commentId);
    
    // Check quote checkbox and show quote content
    setTimeout(() => {
        const quoteContent = document.getElementById('quote-content-' + commentId);
        
        if (quoteCheckbox && quoteContent) {
            quoteCheckbox.checked = true;
            quoteContent.classList.remove('hidden');
        }
        
        // Focus on textarea
        const textarea = document.querySelector('#reply-form-' + commentId + ' textarea[name="content"]');
        if (textarea) {
            textarea.focus();
        }
    }, 100);
}
</script>
@endsection
