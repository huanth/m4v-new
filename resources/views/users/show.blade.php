@extends('layouts.app')

@section('title', $user->username . ' - M4V.ME')
@section('description', 'Trang cá nhân của ' . $user->username . ' trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->username }}</h1>
                    <p class="mt-2 text-gray-600">Trang cá nhân</p>
                </div>
                <a href="{{ url()->previous() }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-center">
                        <!-- Avatar -->
                        <div class="mx-auto mb-4">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover mx-auto">
                            @else
                                <div class="h-24 w-24 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center mx-auto">
                                    <span class="text-3xl font-bold text-white">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Username -->
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $user->username }}</h2>

                        <!-- Role Badge -->
                        <div class="mb-4">
                            @if($user->isSuperAdmin())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Super Admin
                                </span>
                            @elseif($user->isAdmin())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Admin
                                </span>
                            @elseif($user->role === 'SMod')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Super Moderator
                                </span>
                            @elseif($user->role === 'FMod')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Forum Moderator
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Thành viên
                                </span>
                            @endif
                        </div>

                        <!-- Ban Status -->
                        @if($user->isBanned())
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Đã bị ban
                                </span>
                            </div>
                        @endif

                        <!-- Join Date -->
                        <div class="text-sm text-gray-500 mb-4">
                            <p>Tham gia: {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>

                        <!-- Actions -->
                        @if($currentUser && $currentUser->id !== $user->id)
                            <div class="space-y-2">
                                @if($currentUser->canBan($user))
                                    <a href="{{ route('admin.ban.create', $user->id) }}" 
                                       class="inline-flex items-center justify-center w-full px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        Ban user
                                    </a>
                                @endif
                                
                                @if($user->isBanned())
                                    <a href="{{ route('admin.ban.history', $user->id) }}" 
                                       class="inline-flex items-center justify-center w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Lịch sử ban
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Guild Memberships -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bang hội</h3>
                    
                    @if($user->isInGuild())
                        @php
                            $guildMembership = $user->getCurrentGuild();
                        @endphp
                        <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-sm font-bold text-white">
                                        {{ strtoupper(substr($guildMembership->guild->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('guilds.show', $guildMembership->guild->id) }}" class="hover:text-blue-600">
                                        {{ $guildMembership->guild->name }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    @php
                                        $roleNames = [
                                            'leader' => 'Bang chủ',
                                            'vice_leader' => 'Phó bang',
                                            'elder' => 'Trưởng lão',
                                            'member' => 'Thành viên'
                                        ];
                                    @endphp
                                    {{ $roleNames[$guildMembership->role] ?? $guildMembership->role }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="text-xs text-gray-500">
                                    {{ $guildMembership->joined_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa tham gia bang hội nào</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $user->username }} chưa tham gia bang hội nào.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Hoạt động gần đây</h3>
                    
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có hoạt động</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Hoạt động gần đây sẽ được hiển thị ở đây.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
