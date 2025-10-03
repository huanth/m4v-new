@extends('layouts.app')

@section('title', 'Quản lý ' . $guild->name . ' - M4V.ME')
@section('description', 'Quản lý bang hội ' . $guild->name . ' trên M4V.ME')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="">
        
        <!-- Guild Navigation -->
        <x-guild-navigation :guild="$guild" :userMembership="$userMembership" />

        <!-- Guild Banner & Announcement -->
        <x-guild-banner-announcement :guild="$guild" />

        <!-- Guild Settings -->
        <div class="mb-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Cài đặt bang hội</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Banner Management -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Banner bang hội</h3>
                        
                        @if($guild->banner)
                            <div class="mb-4">
                                <img src="{{ Storage::url($guild->banner) }}" alt="Guild Banner" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('guilds.banner.update', $guild->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="banner" class="block text-sm font-medium text-gray-700 mb-1">Chọn banner mới</label>
                                    <input type="file" id="banner" name="banner" accept="image/*" 
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">Định dạng: JPEG, PNG, JPG, GIF. Tối đa 2MB</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                                        Cập nhật banner
                                    </button>
                                    @if($guild->banner)
                                        <button type="submit" name="remove_banner" value="1" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                                            Xóa banner
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Announcement Management -->
                    <div>
                        <h3 class="text-md font-medium text-gray-900 mb-4">Thông báo bang hội</h3>
                        
                        <form method="POST" action="{{ route('guilds.announcement.update', $guild->id) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="announcement" class="block text-sm font-medium text-gray-700 mb-1">Nội dung thông báo</label>
                                    <textarea id="announcement" name="announcement" rows="4" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Nhập thông báo cho bang hội...">{{ $guild->announcement }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Tối đa 1000 ký tự</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">
                                        Cập nhật thông báo
                                    </button>
                                    <button type="submit" name="clear_announcement" value="1" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                                        Xóa thông báo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Role Management -->
            <div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quản lý Role</h2>
                    
                    @if($guild->members->count() > 1)
                        <div class="space-y-4">
                            @foreach($guild->members as $member)
                                @if(!$member->isLeader())
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        @if($member->user->avatar)
                                            <img src="{{ Storage::url($member->user->avatar) }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-xs font-bold text-white">
                                                    {{ strtoupper(substr($member->user->username, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $member->user->username }}</p>
                                            <p class="text-xs text-gray-500">Hiện tại: {{ $member->role_display_name }}</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('guilds.member.role', $guild->id) }}" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                                        <select name="role" class="text-sm border border-gray-300 rounded px-2 py-1">
                                            <option value="{{ $member->role }}" selected>{{ $member->role_display_name }}</option>
                                            @if($member->role !== 'vice_leader')
                                                <option value="vice_leader">Phó bang</option>
                                            @endif
                                            @if($member->role !== 'elder')
                                                <option value="elder">Trưởng lão</option>
                                            @endif
                                            @if($member->role !== 'member')
                                                <option value="member">Thành viên</option>
                                            @endif
                                        </select>
                                        <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                                            Cập nhật
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Chưa có thành viên nào để quản lý role.</p>
                    @endif
                </div>
            </div>

            <!-- Categories Management -->
            <div>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Danh mục bang hội</h2>
                        <button onclick="toggleCategoryForm()" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tạo danh mục
                        </button>
                    </div>

                    <!-- Create Category Form (Hidden by default) -->
                    <div id="categoryForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                        <form method="POST" action="{{ route('guilds.category.create', $guild->id) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
                                    <input type="text" id="name" name="name" required 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                                    <textarea id="description" name="description" rows="3" 
                                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">
                                        Tạo danh mục
                                    </button>
                                    <button type="button" onclick="toggleCategoryForm()" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                                        Hủy
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Categories List -->
                    @if($guild->categories->count() > 0)
                        <div class="space-y-3">
                            @foreach($guild->categories as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $category->name }}</h3>
                                    @if($category->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $category->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">{{ $category->posts_count }} bài viết</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('guilds.category.delete', [$guild->id, $category->id]) }}" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Chưa có danh mục nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Chỉnh sửa danh mục</h3>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
                        <input type="text" id="edit_name" name="name" required 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <textarea id="edit_description" name="description" rows="3" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700">
                            Cập nhật
                        </button>
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                            Hủy
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCategoryForm() {
    const form = document.getElementById('categoryForm');
    form.classList.toggle('hidden');
}

function editCategory(id, name, description) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('editCategoryForm').action = '{{ route("guilds.category.update", [$guild->id, ":categoryId"]) }}'.replace(':categoryId', id);
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
}
</script>
@endsection
