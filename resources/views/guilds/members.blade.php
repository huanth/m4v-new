@extends('layouts.app')

@section('title', 'Thành viên - ' . $guild->name)
@section('description', 'Danh sách thành viên bang hội ' . $guild->name)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="">

        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$userMembership" />

        <!-- Guild Banner & Announcement -->
        <x-guild-banner-announcement :guild="$guild" />

        <!-- Guild Info -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr($guild->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $guild->name }}</h2>
                    <p class="text-gray-600">{{ $guild->description }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $guild->member_count }} thành viên
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Thành lập {{ $guild->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members by Role -->
        <div class="space-y-8">
            @php
                $roleOrder = ['leader', 'vice_leader', 'elder', 'member'];
                $roleNames = [
                    'leader' => 'Bang chủ',
                    'vice_leader' => 'Phó bang',
                    'elder' => 'Trưởng lão',
                    'member' => 'Thành viên'
                ];
                $roleColors = [
                    'leader' => 'bg-yellow-100 text-yellow-800',
                    'vice_leader' => 'bg-purple-100 text-purple-800',
                    'elder' => 'bg-blue-100 text-blue-800',
                    'member' => 'bg-gray-100 text-gray-800'
                ];
            @endphp

            @foreach($roleOrder as $role)
                @if($membersByRole->has($role))
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$role] }} mr-3">
                                    {{ $roleNames[$role] }}
                                </span>
                                {{ $membersByRole[$role]->count() }} người
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($membersByRole[$role] as $member)
                                    <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex-shrink-0">
                                            @if($member->user->avatar)
                                                <img src="{{ Storage::url($member->user->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-white">
                                                        {{ strtoupper(substr($member->user->username, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    <a href="{{ route('user.show', $member->user->id) }}" class="hover:text-blue-600">
                                                        {{ $member->user->username }}
                                                    </a>
                                                </p>
                                                @if($member->user->isSuperAdmin())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        SA
                                                    </span>
                                                @elseif($member->user->isAdmin())
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Admin
                                                    </span>
                                                @elseif($member->user->role === 'SMod')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        SMod
                                                    </span>
                                                @elseif($member->user->role === 'FMod')
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        FMod
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                Tham gia {{ $member->joined_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        @if($userMembership && $userMembership->isAdmin() && $member->role !== 'leader')
                                            <div class="flex-shrink-0">
                                                <div class="relative">
                                                    <button onclick="toggleRoleDropdown({{ $member->id }})" 
                                                            class="p-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                        </svg>
                                                    </button>
                                                    <div id="roleDropdown-{{ $member->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                        <div class="py-1">
                                                            @if($member->role === 'member')
                                                                <form method="POST" action="{{ route('guilds.member.role', $guild->id) }}" class="block">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                                                                    <input type="hidden" name="role" value="elder">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Thăng làm Trưởng lão
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if($member->role === 'elder')
                                                                <form method="POST" action="{{ route('guilds.member.role', $guild->id) }}" class="block">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                                                                    <input type="hidden" name="role" value="vice_leader">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Thăng làm Phó bang
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if($member->role === 'vice_leader')
                                                                <form method="POST" action="{{ route('guilds.member.role', $guild->id) }}" class="block">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                                                                    <input type="hidden" name="role" value="elder">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Hạ xuống Trưởng lão
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if($member->role !== 'member')
                                                                <form method="POST" action="{{ route('guilds.member.role', $guild->id) }}" class="block">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id" value="{{ $member->user_id }}">
                                                                    <input type="hidden" name="role" value="member">
                                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Hạ xuống Thành viên
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<script>
function toggleRoleDropdown(memberId) {
    // Close all other dropdowns
    document.querySelectorAll('[id^="roleDropdown-"]').forEach(dropdown => {
        if (dropdown.id !== `roleDropdown-${memberId}`) {
            dropdown.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    const dropdown = document.getElementById(`roleDropdown-${memberId}`);
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleRoleDropdown"]') && !event.target.closest('[id^="roleDropdown-"]')) {
        document.querySelectorAll('[id^="roleDropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});
</script>
@endsection
