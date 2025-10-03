@extends('layouts.app')

@section('title', 'Ban User - M4V.ME')
@section('description', 'Ban user với lý do và thời hạn')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Ban User</h1>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Quay lại
                    </a>
                </div>

                <!-- User Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Thông tin User</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-lg font-medium text-gray-700">
                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-medium text-gray-900">
                                {{ $user->username }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $user->email }} • {{ $user->getRoleDisplayName() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ban Form -->
                <form method="POST" action="{{ route('admin.ban.store', $user) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Lý do ban <span class="text-red-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" required rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror"
                                  placeholder="Nhập lý do ban chi tiết...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Duration Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Thời hạn ban <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input id="permanent" name="duration_type" type="radio" value="permanent" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                       {{ old('duration_type') === 'permanent' ? 'checked' : '' }}>
                                <label for="permanent" class="ml-2 block text-sm text-gray-900">
                                    Vĩnh viễn
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="temporary" name="duration_type" type="radio" value="temporary" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                       {{ old('duration_type') === 'temporary' ? 'checked' : '' }}>
                                <label for="temporary" class="ml-2 block text-sm text-gray-900">
                                    Tạm thời
                                </label>
                            </div>
                        </div>
                        @error('duration_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration Details (for temporary) -->
                    <div id="duration-details" class="hidden space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="duration_value" class="block text-sm font-medium text-gray-700 mb-2">
                                    Số lượng
                                </label>
                                <input type="number" id="duration_value" name="duration_value" min="1" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('duration_value') border-red-500 @enderror"
                                       value="{{ old('duration_value') }}">
                                @error('duration_value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="duration_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Đơn vị
                                </label>
                                <select id="duration_unit" name="duration_unit" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('duration_unit') border-red-500 @enderror">
                                    <option value="">Chọn đơn vị</option>
                                    <option value="minutes">Phút</option>
                                    <option value="hours">Giờ</option>
                                    <option value="days">Ngày</option>
                                    <option value="weeks">Tuần</option>
                                    <option value="months">Tháng</option>
                                    <option value="years">Năm</option>
                                </select>
                                @error('duration_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                            Hủy
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                onclick="return confirm('Bạn có chắc muốn ban user này?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                            </svg>
                            Ban User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const permanentRadio = document.getElementById('permanent');
    const temporaryRadio = document.getElementById('temporary');
    const durationDetails = document.getElementById('duration-details');
    
    function toggleDurationDetails() {
        if (temporaryRadio.checked) {
            durationDetails.classList.remove('hidden');
        } else {
            durationDetails.classList.add('hidden');
        }
    }
    
    permanentRadio.addEventListener('change', toggleDurationDetails);
    temporaryRadio.addEventListener('change', toggleDurationDetails);
    
    // Initial state
    toggleDurationDetails();
});
</script>
@endsection
