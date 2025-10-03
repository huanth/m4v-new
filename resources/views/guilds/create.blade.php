@extends('layouts.app')

@section('title', 'Tạo bang hội - M4V.ME')
@section('description', 'Tạo bang hội mới trên M4V.ME và trở thành bang chủ')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tạo bang hội</h1>
                    <p class="mt-2 text-gray-600">
                        Tạo bang hội mới và trở thành bang chủ
                    </p>
                </div>
                <a href="{{ route('guilds.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay lại
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-lg p-8">
            <form method="POST" action="{{ route('guilds.store') }}" class="space-y-6">
                @csrf
                
                <!-- Guild Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên bang hội <span class="text-red-500">*</span>
                    </label>
                    <input id="name" name="name" type="text" required 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 focus:ring-red-500 @enderror" 
                           placeholder="Nhập tên bang hội" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả bang hội
                    </label>
                    <textarea id="description" name="description" rows="4" 
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 focus:ring-red-500 @enderror" 
                              placeholder="Mô tả về bang hội của bạn...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                                Thông tin quan trọng
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Bạn sẽ trở thành bang chủ của bang hội này</li>
                                    <li>Tên bang hội phải là duy nhất trên hệ thống</li>
                                    <li>Bạn có thể quản lý thành viên và cài đặt bang hội</li>
                                    <li>Bang hội sẽ được hiển thị công khai cho tất cả người dùng</li>
                                    <li>Không giới hạn số thành viên có thể gia nhập</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('guilds.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tạo bang hội
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
