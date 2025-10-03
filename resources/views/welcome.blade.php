<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>M4V.ME - Cộng đồng đích thực</title>
        
        <!-- SEO Meta Tags -->
        <meta name="description" content="M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức. Tham gia ngay để trải nghiệm cộng đồng tuyệt vời!">
        <meta name="keywords" content="M4V, cộng đồng, forum, chia sẻ, kiến thức, mua bán, cộng đồng đích thực">
        <meta name="author" content="M4V.ME">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="M4V.ME - Cộng đồng đích thực">
        <meta property="og:description" content="M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức. Tham gia ngay để trải nghiệm cộng đồng tuyệt vời!">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="M4V.ME">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="M4V.ME - Cộng đồng đích thực">
        <meta name="twitter:description" content="M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức. Tham gia ngay để trải nghiệm cộng đồng tuyệt vời!">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-800 min-h-screen">
        {{-- Header --}}
        @include('components.header')
        
        {{-- Ban Notification --}}
        @include('components.ban-notification')
        
        <div class="min-h-screen py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">M4V.ME - Cộng đồng đích thực</h1>
                    <p class="text-xl text-gray-600">Nơi kết nối và chia sẻ kiến thức</p>
                </div>

                <!-- Latest Posts Section -->
                @if($latestPosts->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Bài viết mới nhất</h2>
                        <div class="space-y-4">
                            @foreach($latestPosts as $post)
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
                                            <!-- Guild Info Badge -->
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                                </svg>
                                                {{ $post->guild->name }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                            <a href="{{ route('guilds.posts.show', [$post->guild->id, $post->id]) }}" 
                                               class="hover:text-blue-600 transition-colors">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex items-center space-x-2">
                                                    @if($post->user->avatar)
                                                        <img src="{{ Storage::url($post->user->avatar) }}" alt="Avatar" class="h-5 w-5 rounded-full object-cover">
                                                    @else
                                                        <div class="h-5 w-5 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                            <span class="text-xs font-bold text-white">
                                                                {{ strtoupper(substr($post->user->username, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <span>{{ $post->user->username }}</span>
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
                                                    {{ $post->getTotalCommentsCount() }}
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
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>