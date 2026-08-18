@extends('layouts.app')

@section('title', 'Thành viên bị ban - ' . $guild->name)
@section('description', 'Danh sách thành viên bị ban khỏi bang hội ' . $guild->name)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="">

        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$guild->members()->where('user_id', auth()->id())->first()" />

        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Thành viên bị ban</h2>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành viên</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lý do</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ban bởi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày ban</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bans as $ban)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $ban->user->username ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $ban->reason ?: '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                    {{ $ban->bannedBy->username ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ban->banned_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" action="{{ route('guilds.member.unban', $guild->id) }}" class="inline"
                                          onsubmit="return confirm('Gỡ ban cho thành viên này?')">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $ban->user_id }}">
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            Gỡ ban
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Chưa có thành viên nào bị ban.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
