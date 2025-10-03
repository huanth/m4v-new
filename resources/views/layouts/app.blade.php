<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức')">
    <meta name="keywords" content="@yield('keywords', 'M4V, cộng đồng, forum, chia sẻ, kiến thức, mua bán')">
    <meta name="author" content="M4V.ME">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', config('app.name', 'Laravel'))">
    <meta property="og:description" content="@yield('description', 'M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="M4V.ME">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title', config('app.name', 'Laravel'))">
    <meta name="twitter:description" content="@yield('description', 'M4V.ME - Cộng đồng đích thực, nơi kết nối và chia sẻ kiến thức')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 min-h-screen">
    {{-- Header --}}
    @include('components.header')
    
    <main class="mx-auto max-w-[1440px]">
        @include('components.ban-notification')
        @yield('content')
    </main>

    @auth
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentUserId = {{ auth()->id() }};

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
    @endauth
</body>
</html>
