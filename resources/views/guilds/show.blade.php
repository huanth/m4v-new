@extends('layouts.app')

@section('title', $guild->name . ' - M4V.ME')
@section('description', $guild->description ?: 'Tham gia bang hội ' . $guild->name . ' trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="">
        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$userMembership" />
        
        <!-- Guild Banner & Announcement -->
        <x-guild-banner-announcement :guild="$guild" />

        <!-- Posts Section -->
        <div class=" mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Categories Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh mục</h3>
                        
                        @if($guild->categories->count() > 0)
                            <div class="space-y-2">
                                <!-- All Posts -->
                                <a href="{{ route('guilds.show', $guild->id) }}" 
                                   class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors {{ !request('category') ? 'bg-blue-50 border border-blue-200' : '' }}">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">Tất cả bài viết</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $guild->posts->count() }}</span>
                                </a>
                                
                                @foreach($guild->categories as $category)
                                    <a href="{{ route('guilds.show', ['id' => $guild->id, 'category' => $category->id]) }}" 
                                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors {{ request('category') == $category->id ? 'bg-blue-50 border border-blue-200' : '' }}">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $category->posts_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Chưa có danh mục nào</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Posts Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                @if(request('category'))
                                    @php
                                        $selectedCategory = $guild->categories->where('id', request('category'))->first();
                                    @endphp
                                    @if($selectedCategory)
                                        {{ $selectedCategory->name }}
                                    @else
                                        Bài viết bang hội
                                    @endif
                                @else
                                    Bài viết bang hội
                                @endif
                            </h2>
                            @auth
                                @if($userMembership)
                                    <a href="{{ route('guilds.posts.create', $guild->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tạo bài viết
                                    </a>
                                @endif
                            @endauth
                        </div>

                @if($guild->posts->count() > 0)
                    <div class="space-y-4">
                        @foreach($guild->posts as $post)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
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
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        <a href="{{ route('guilds.posts.show', [$guild->id, $post->id]) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                @if($post->author->avatar)
                                                    <img src="{{ Storage::url($post->author->avatar) }}" alt="Avatar" class="h-5 w-5 rounded-full object-cover">
                                                @else
                                                    <div class="h-5 w-5 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <span class="text-xs font-bold text-white">
                                                            {{ strtoupper(substr($post->author->username, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <span>{{ $post->author->username }}</span>
                                            </div>
                                            <span>•</span>
                                            <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                {{ $post->likes_count }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $post->comments_count }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $post->views_count }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bài viết nào</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Hãy tạo bài viết đầu tiên cho bang hội.
                        </p>
                        @auth
                            @if($userMembership)
                                <div class="mt-6">
                                    <a href="{{ route('guilds.posts.create', $guild->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tạo bài viết đầu tiên
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
