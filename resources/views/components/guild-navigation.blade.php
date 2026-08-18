@props(['guild', 'userMembership'])

<div class="mb-2">
    <!-- Trang chủ bang hội -->
    <a href="{{ route('guilds.show', $guild->id) }}" 
       class="inline-flex items-center text-sm font-medium text-blue-500 focus:outline-none mr-2 mb-2">
        {{ $guild->name }}
    </a>

    <!-- Thành viên (public) -->
    <a href="{{ route('guilds.members', $guild->id) }}" 
       class="inline-flex items-center text-sm font-medium text-gray-700 focus:outline-none mr-2 mb-2">
       🔹Thành viên
    </a>

    @auth
        @if($userMembership)
            <!-- Quản lý bang hội(SAdmin, Admin, Leader, Vice Leader) -->
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || $userMembership->canManageRoles())
            <a href="{{ route('guilds.manage', $guild->id) }}" 
               class="inline-flex items-center text-sm font-medium text-gray-700 focus:outline-none mr-2 mb-2">
               🔹Quản lý
            </a>
            @endif

            <!-- Danh sách thành viên bị ban ở bang hội -->
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || $userMembership->canManageRoles())
            <a href="{{ route('guilds.banned', $guild->id) }}"
               class="inline-flex items-center text-sm font-medium text-gray-700 focus:outline-none mr-2 mb-2">
               🔹Thành viên bị ban
            </a>
            @endif

            <!-- Rời bang hội -->
            <form method="POST" action="{{ route('guilds.leave', $guild->id) }}" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center text-sm font-medium text-red-700 focus:outline-none mr-2 mb-2 hover:text-red-800"
                        onclick="return confirm('Bạn có chắc muốn rời bang hội này?')">
                    🔹Rời bang
                </button>
            </form>
        @else
            <!-- Gia nhập bang hội (chỉ hiển thị khi chưa là thành viên) -->
            <form method="POST" action="{{ route('guilds.join', $guild->id) }}" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center text-sm font-medium text-green-700 focus:outline-none mr-2 mb-2 hover:text-green-800">
                    🔹Gia nhập
                </button>
            </form>
        @endif
    @else
        <!-- Đăng nhập để gia nhập -->
        <a href="{{ route('login') }}" 
           class="inline-flex items-center text-sm font-medium text-green-700 focus:outline-none mr-2 mb-2 hover:text-green-800">
            🔹Đăng nhập để gia nhập
        </a>
    @endauth
</div>
