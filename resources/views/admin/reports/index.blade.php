@extends('layouts.app')

@section('title', 'Quản lý báo cáo - M4V.ME')
@section('description', 'Danh sách các báo cáo vi phạm đang chờ xử lý')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Quản lý báo cáo</h1>
                    <a href="{{ route('profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-4">
                    @forelse($reports as $report)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        @switch($report->reportable_type)
                                            @case('post')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Bài viết</span>
                                                @break
                                            @case('comment')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Bình luận</span>
                                                @break
                                            @case('user')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Người dùng</span>
                                                @break
                                        @endswitch
                                        <span class="text-xs text-gray-500">
                                            Báo cáo bởi
                                            <span class="font-medium text-gray-700">{{ $report->reporter->username ?? 'N/A' }}</span>
                                            · {{ $report->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-800 mb-2">{{ $report->reason }}</p>

                                    @php $target = $report->reportable; @endphp
                                    @if($target)
                                        <div class="bg-gray-50 border-l-4 border-gray-300 pl-3 py-2 rounded-r-md text-xs text-gray-600">
                                            @if($report->reportable_type === 'post')
                                                <a href="{{ route('guilds.posts.show', [$target->guild_id, $target->id]) }}" class="text-blue-600 hover:underline font-medium">
                                                    {{ $target->title }}
                                                </a>
                                            @elseif($report->reportable_type === 'comment')
                                                <a href="{{ route('guilds.posts.show', [$target->post->guild_id, $target->post_id]) }}" class="text-blue-600 hover:underline font-medium">
                                                    Xem bình luận trong bài viết
                                                </a>
                                                <p class="mt-1 italic">{{ Str::limit($target->content, 150) }}</p>
                                            @elseif($report->reportable_type === 'user')
                                                <a href="{{ route('user.profile', $target->id) }}" class="text-blue-600 hover:underline font-medium">
                                                    {{ $target->username }}
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">Nội dung đã bị xóa.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex items-center space-x-2">
                                <form method="POST" action="{{ route('reports.resolve', $report) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                        Đã xử lý
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('reports.dismiss', $report) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Bỏ qua
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            Không có báo cáo nào đang chờ xử lý.
                        </div>
                    @endforelse
                </div>

                @if($reports->hasPages())
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
