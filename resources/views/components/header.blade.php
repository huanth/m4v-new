<header class="border-b border-gray-200" style="background-color: #036a95;">
    <div class="container mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between py-3 px-4">
            <!-- Search Section -->
            <div class="flex items-center space-x-2">
                <input 
                    type="text" 
                    placeholder="Nhập để tìm kiếm" 
                    class="px-3 py-2 border border-gray-300 rounded text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                >
                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white font-medium transition-colors">
                    Tìm
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex items-center space-x-4 flex-wrap">
                <a href="{{ url('/') }}" class="text-white hover:text-gray-200 transition-colors whitespace-nowrap {{ request()->is('/') ? '!text-[#f55721]' : '' }}">TRANG CHỦ</a>
                @auth
                    <a href="{{ route('guilds.index') }}" class="text-white hover:text-gray-200 transition-colors whitespace-nowrap {{ request()->is('guilds*') ? '!text-[#f55721]' : '' }}">BANG HỘI</a>
                @endauth
                @auth
                    @php
                        $user = auth()->user();
                        $clearedNotifications = session('cleared_notifications', []);
                        $banCount = ($user->isBanned() && !in_array('ban', $clearedNotifications) && !in_array('all', $clearedNotifications)) ? 1 : 0;
                        $unreadNotificationsCount = $user->getUnreadNotificationsCount();
                        $totalNotifications = $banCount + $unreadNotificationsCount;
                    @endphp
                    <a href="{{ route('notifications') }}" 
                       class="text-white hover:text-gray-200 transition-colors whitespace-nowrap relative {{ request()->is('notifications') ? '!text-[#f55721]' : '' }}">
                        HOẠT ĐỘNG
                        @if($totalNotifications > 0)
                            (<span class="text-[#FF0000]">
                                {{ $totalNotifications }}
                            </span>)
                        @endif
                    </a>
                @endauth
                @auth
                    @php
                        $unreadConversationsCount = $user->getUnreadConversationsCount();
                    @endphp
                    <a href="{{ route('chat.index') }}" 
                       class="text-white hover:text-gray-200 transition-colors whitespace-nowrap relative {{ request()->is('chat*') ? '!text-[#f55721]' : '' }}">
                        TIN NHẮN
                        @if($unreadConversationsCount > 0)
                            (<span class="text-[#FF0000]">
                                {{ $unreadConversationsCount }}
                            </span>)
                        @endif
                    </a>
                    <a href="{{ route('profile') }}" class="text-white hover:text-gray-200 transition-colors whitespace-nowrap {{ request()->is('profile') ? '!text-[#f55721]' : '' }}">TRANG CÁ NHÂN</a>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-white hover:text-gray-200 transition-colors whitespace-nowrap">THOÁT</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200 transition-colors whitespace-nowrap {{ request()->is('login') ? '!text-[#f55721]' : '' }}">ĐĂNG NHẬP</a>
                    <a href="{{ route('register') }}" class="text-white hover:text-gray-200 transition-colors whitespace-nowrap {{ request()->is('register') ? '!text-[#f55721]' : '' }}">ĐĂNG KÝ</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
