@extends('layouts.app')

@section('title', 'Dashboard - M4V.ME')
@section('description', 'Trang quản lý cá nhân của bạn trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $user->role }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- User Info Card -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Thông tin cá nhân</h3>
                                <p class="text-sm text-gray-600">Xem và chỉnh sửa thông tin</p>
                            </div>
                        </div>
                    </div>

                    <!-- Posts Card -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Bài viết</h3>
                                <p class="text-sm text-gray-600">Quản lý bài viết của bạn</p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Card -->
                    <div class="bg-purple-50 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Tin nhắn</h3>
                                <p class="text-sm text-gray-600">Xem tin nhắn mới</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Thông tin tài khoản</h2>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tên đăng nhập</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->username }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vai trò</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->role }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                @if($user->isAdmin())
                <!-- Admin Panel -->
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <h2 class="text-lg font-medium text-yellow-900 mb-4">Bảng điều khiển quản trị</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="bg-yellow-100 hover:bg-yellow-200 rounded-lg p-4 transition-colors">
                            <h3 class="font-medium text-yellow-900">Quản lý người dùng</h3>
                            <p class="text-sm text-yellow-700">Xem và quản lý tài khoản người dùng</p>
                        </a>
                        <a href="#" class="bg-yellow-100 hover:bg-yellow-200 rounded-lg p-4 transition-colors">
                            <h3 class="font-medium text-yellow-900">Quản lý bài viết</h3>
                            <p class="text-sm text-yellow-700">Kiểm duyệt và quản lý nội dung</p>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection