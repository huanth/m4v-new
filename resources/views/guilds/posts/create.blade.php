@extends('layouts.app')

@section('title', 'Tạo bài viết - ' . $guild->name)
@section('description', 'Tạo bài viết mới trong bang hội ' . $guild->name)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tạo bài viết mới</h1>
                    <p class="mt-2 text-gray-600">
                        Tạo bài viết trong bang hội <span class="font-semibold">{{ $guild->name }}</span>
                    </p>
                </div>
                <a href="{{ route('guilds.show', $guild->id) }}" 
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

        <!-- Guild Banner & Announcement -->
        <x-guild-banner-announcement :guild="$guild" />

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-lg p-8">
            <form method="POST" action="{{ route('guilds.posts.store', $guild->id) }}" class="space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Tiêu đề bài viết <span class="text-red-500">*</span>
                    </label>
                    <input id="title" name="title" type="text" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 focus:ring-red-500 @enderror" 
                           placeholder="Nhập tiêu đề bài viết" value="{{ old('title') }}">
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($guild->categories->count() == 0)
                        <p class="mt-2 text-sm text-gray-500">
                            Chưa có danh mục nào. 
                            <a href="{{ route('guilds.show', $guild->id) }}" class="text-blue-600 hover:text-blue-500">
                                Tạo danh mục trước
                            </a>
                        </p>
                    @endif
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Nội dung bài viết <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="12" required 
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 focus:ring-red-500 @enderror" 
                              placeholder="Nhập nội dung bài viết...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Tối đa 10,000 ký tự. Hiện tại: <span id="charCount">0</span> ký tự.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Hướng dẫn tạo bài viết
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Tiêu đề phải rõ ràng và mô tả nội dung bài viết</li>
                                    <li>Chọn danh mục phù hợp để dễ dàng tìm kiếm</li>
                                    <li>Nội dung phải có ý nghĩa và tuân thủ quy định bang hội</li>
                                    <li>Bài viết sẽ được hiển thị công khai cho tất cả thành viên</li>
                                    <li>Bạn có thể chỉnh sửa bài viết sau khi tạo</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('guilds.show', $guild->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tạo bài viết
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
