@extends('layouts.app')

@section('title', $user->username . ' - M4V.ME')
@section('description', 'Profile của ' . $user->username . ' trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <!-- Header Section -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 h-20 w-20">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover">
                            @else
                                <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">
                                        {{ strtoupper(substr($user->username, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- User Info -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->username }}</h1>
                            <div class="flex items-center space-x-4 mt-2">
                                @switch($user->role)
                                    @case('SADMIN')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Super Admin
                                        </span>
                                        @break
                                    @case('ADMIN')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Admin
                                        </span>
                                        @break
                                    @case('SMod')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                            </svg>
                                            Super Moderator
                                        </span>
                                        @break
                                    @case('FMod')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                            </svg>
                                            Forum Moderator
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Thành viên
                                        </span>
                                @endswitch
                                
                                @if($user->isBanned())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Bị ban
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Hoạt động
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        @auth
                            @if($currentUser && $currentUser->id !== $user->id)
                                @if($currentUser->canBan($user))
                                    @if($user->isBanned())
                                        <form method="POST" action="{{ route('admin.ban.unban', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                    onclick="return confirm('Bạn có chắc muốn unban user này?')">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Unban
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.ban.create', $user) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                            Ban
                                        </a>
                                    @endif
                                @endif
                                
                                <a href="{{ route('chat.show', $user) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Nhắn tin
                                </a>
                                
                                <a href="{{ route('user.ban.history', $user) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Lịch sử ban
                                </a>
                            @endif
                            
                            @if($currentUser && $currentUser->id === $user->id)
                                <a href="{{ route('profile') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Chỉnh sửa
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Đăng nhập để tương tác
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - User Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Info -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin cơ bản</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Tên đăng nhập</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Ngày tham gia</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Lần hoạt động cuối</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Stats -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h2 class="text-xl font-semibold text-blue-800 mb-4">Thống kê hoạt động</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-900">0</div>
                                    <div class="text-sm text-blue-700">Bài viết</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-900">0</div>
                                    <div class="text-sm text-blue-700">Bình luận</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-900">0</div>
                                    <div class="text-sm text-blue-700">Điểm uy tín</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-900">0</div>
                                    <div class="text-sm text-blue-700">Lượt thích</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white border border-gray-200 p-6 rounded-lg">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Hoạt động gần đây</h2>
                            <div class="text-center text-gray-500 py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2">Chưa có hoạt động nào</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Admin/Moderator Info -->
                    <div class="space-y-6">
                        @auth
                            @if($currentUser && $currentUser->canBan($user))
                                <!-- Ban History -->
                                @if($banHistory->count() > 0)
                                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                                        <h3 class="text-lg font-semibold text-red-800 mb-4">Lịch sử ban</h3>
                                        <div class="space-y-3">
                                            @foreach($banHistory->take(3) as $ban)
                                                <div class="bg-white p-3 rounded border border-red-200">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="text-sm font-medium text-red-900">{{ $ban->reason }}</p>
                                                            <p class="text-xs text-red-700">{{ $ban->banned_at->format('d/m/Y') }}</p>
                                                        </div>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                            @if($ban->is_active && !$ban->isExpired()) bg-red-100 text-red-800
                                                            @elseif($ban->isExpired()) bg-yellow-100 text-yellow-800
                                                            @else bg-gray-100 text-gray-800 @endif">
                                                            {{ $ban->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($banHistory->count() > 3)
                                                <a href="{{ route('user.ban.history', $user) }}" 
                                                   class="text-sm text-red-600 hover:text-red-800">
                                                    Xem tất cả ({{ $banHistory->count() }})
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Current Ban Info -->
                                @if($activeBan)
                                    <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                                        <h3 class="text-lg font-semibold text-red-800 mb-4">Ban hiện tại</h3>
                                        <div class="space-y-2">
                                            <p class="text-sm"><span class="font-medium">Lý do:</span> {{ $activeBan->reason }}</p>
                                            <p class="text-sm"><span class="font-medium">Thời hạn:</span> {{ $activeBan->duration }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endauth

                        <!-- Role-specific Features -->
                        @if($user->isSuperAdmin())
                            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                                <h3 class="text-lg font-semibold text-red-800 mb-4">Super Admin</h3>
                                <p class="text-sm text-red-700 mb-4">Có quyền quản lý toàn bộ hệ thống</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Quản lý tất cả user
                                    </div>
                                    <div class="flex items-center text-sm text-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Cấu hình hệ thống
                                    </div>
                                    <div class="flex items-center text-sm text-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Truy cập Dashboard
                                    </div>
                                </div>
                            </div>
                        @elseif($user->isAdmin())
                            <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
                                <h3 class="text-lg font-semibold text-orange-800 mb-4">Admin</h3>
                                <p class="text-sm text-orange-700 mb-4">Có quyền quản lý user và nội dung</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-orange-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Quản lý user (trừ Super Admin)
                                    </div>
                                    <div class="flex items-center text-sm text-orange-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Quản lý nội dung
                                    </div>
                                </div>
                            </div>
                        @elseif($user->role === 'SMod')
                            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4">Super Moderator</h3>
                                <p class="text-sm text-blue-700 mb-4">Có quyền kiểm duyệt toàn diện</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Kiểm duyệt tất cả forum
                                    </div>
                                    <div class="flex items-center text-sm text-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ban FMod và User
                                    </div>
                                </div>
                            </div>
                        @elseif($user->role === 'FMod')
                            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                                <h3 class="text-lg font-semibold text-green-800 mb-4">Forum Moderator</h3>
                                <p class="text-sm text-green-700 mb-4">Có quyền kiểm duyệt forum được phân công</p>
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm text-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Kiểm duyệt forum
                                    </div>
                                    <div class="flex items-center text-sm text-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ban User
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
