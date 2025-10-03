@extends('layouts.app')

@section('title', 'Chỉnh sửa bài viết - ' . $guild->name)
@section('description', 'Chỉnh sửa bài viết: ' . $post->title . ' trong bang hội ' . $guild->name)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa bài viết</h1>
                    <p class="mt-2 text-gray-600">
                        Chỉnh sửa bài viết trong bang hội <span class="font-semibold">{{ $guild->name }}</span>
                    </p>
                </div>
                <a href="{{ route('guilds.posts.show', [$guild->id, $post->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$userMembership" />

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-lg p-8">
            <form method="POST" action="{{ route('guilds.posts.update', [$guild->id, $post->id]) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Tiêu đề bài viết <span class="text-red-500">*</span>
                    </label>
                    <input id="title" name="title" type="text" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 focus:ring-red-500 @enderror" 
                           placeholder="Nhập tiêu đề bài viết" value="{{ old('title', $post->title) }}">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Danh mục
                    </label>
                    <select id="category_id" name="category_id" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 focus:ring-red-500 @enderror">
                        <option value="">-- Chọn danh mục (tùy chọn) --</option>
                        @foreach($guild->categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Nội dung bài viết <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="12" required 
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 focus:ring-red-500 @enderror" 
                              placeholder="Nhập nội dung bài viết...">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Tối đa 10,000 ký tự. Hiện tại: <span id="charCount">{{ strlen($post->content) }}</span> ký tự.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Lưu ý khi chỉnh sửa
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Thời gian chỉnh sửa sẽ được ghi nhận</li>
                                    <li>Nội dung cũ sẽ bị thay thế hoàn toàn</li>
                                    <li>Hãy kiểm tra kỹ trước khi lưu</li>
                                    <li>Bài viết đã ghim/khóa vẫn có thể chỉnh sửa</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('guilds.posts.show', [$guild->id, $post->id]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Cập nhật bài viết
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Character counter
document.getElementById('content').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
    
    if (charCount > 10000) {
        this.value = this.value.substring(0, 10000);
        document.getElementById('charCount').textContent = '10000';
    }
});
</script>
@endsection
