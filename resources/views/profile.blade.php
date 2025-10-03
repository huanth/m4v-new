@extends('layouts.app')

@section('title', 'Trang Cá Nhân - M4V.ME')
@section('description', 'Quản lý thông tin cá nhân và tài khoản của bạn trên M4V.ME')

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
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Trang Cá Nhân</h1>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('user.ban.history', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Lịch sử ban
                        </a>
                    </div>
                </div>

                <!-- Avatar Section -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex items-center space-x-6">
                        <!-- Current Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center relative group">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-white">
                                        {{ strtoupper(substr($user->username, 0, 2)) }}
                                    </span>
                                @endif
                                
                                <!-- Upload Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avatar Info & Upload -->
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ảnh đại diện</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                @if($user->avatar)
                                    Bạn đã có ảnh đại diện. Nhấp vào ảnh để thay đổi.
                                @else
                                    Bạn chưa có ảnh đại diện. Tải lên ảnh để cá nhân hóa profile.
                                @endif
                            </p>
                            
                            <!-- Upload Form -->
                            <form id="avatar-form" method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                
                                <div class="flex items-center space-x-3">
                                    <input type="file" 
                                           id="avatar-input" 
                                           name="avatar" 
                                           accept="image/*" 
                                           class="hidden"
                                           onchange="document.getElementById('avatar-form').submit()">
                                    
                                    <label for="avatar-input" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Chọn ảnh
                                    </label>
                                    
                                    @if($user->avatar)
                                        <button type="button" 
                                                onclick="if(confirm('Bạn có chắc muốn xóa ảnh đại diện?')) { document.getElementById('remove-avatar-form').submit(); }"
                                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Xóa ảnh
                                        </button>
                                    @endif
                                </div>
                                
                                <p class="text-xs text-gray-400">
                                    Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB
                                </p>
                            </form>
                            
                            <!-- Remove Avatar Form -->
                            @if($user->avatar)
                                <form id="remove-avatar-form" method="POST" action="{{ route('profile.avatar.remove') }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Thông tin tài khoản -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin tài khoản</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tên đăng nhập:</span>
                                <span class="font-medium text-gray-900">{{ $user->username }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vai trò:</span>
                                <span class="font-medium text-gray-900">
                                    @switch($user->role)
                                        @case('SADMIN')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Super Admin
                                            </span>
                                            @break
                                        @case('ADMIN')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Admin
                                            </span>
                                            @break
                                        @case('SMod')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Super Moderator
                                            </span>
                                            @break
                                        @case('FMod')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Forum Moderator
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Thành viên
                                            </span>
                                    @endswitch
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngày tham gia:</span>
                                <span class="font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê hoạt động -->
                    <div class="bg-blue-50 p-6 rounded-lg shadow border border-blue-200">
                        <h2 class="text-xl font-semibold text-blue-800 mb-4">Thống kê hoạt động</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-blue-700">Bài viết đã đăng:</span>
                                <span class="font-medium text-blue-900">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Bình luận:</span>
                                <span class="font-medium text-blue-900">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Điểm uy tín:</span>
                                <span class="font-medium text-blue-900">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-700">Lần đăng nhập cuối:</span>
                                <span class="font-medium text-blue-900">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Các tính năng dành cho Super Admin -->
                @if($user->isSuperAdmin())
                    <div class="mt-6 bg-red-50 p-6 rounded-lg shadow border border-red-200">
                        <h2 class="text-xl font-semibold text-red-800 mb-4">Bảng điều khiển Super Admin</h2>
                        <p class="text-red-700 mb-4">Bạn có quyền truy cập đầy đủ các tính năng quản trị hệ thống.</p>
                        <div class="flex space-x-4">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Các tính năng dành cho Admin -->
                @if($user->isAdmin() && !$user->isSuperAdmin())
                    <div class="mt-6 bg-orange-50 p-6 rounded-lg shadow border border-orange-200">
                        <h2 class="text-xl font-semibold text-orange-800 mb-4">Bảng điều khiển Admin</h2>
                        <p class="text-orange-700 mb-4">Bạn có quyền quản lý một số tính năng của hệ thống.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Quản lý người dùng
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Quản lý nội dung
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Các tính năng dành cho Moderator -->
                @if(($user->role === 'SMod' || $user->role === 'FMod') && !$user->isAdmin())
                    <div class="mt-6 bg-green-50 p-6 rounded-lg shadow border border-green-200">
                        <h2 class="text-xl font-semibold text-green-800 mb-4">Bảng điều khiển Moderator</h2>
                        <p class="text-green-700 mb-4">Bạn có quyền kiểm duyệt và quản lý nội dung trong phạm vi được phân quyền.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Kiểm duyệt nội dung
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Quản lý báo cáo
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
