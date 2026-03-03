<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guild;
use App\Models\GuildCategory;

class GuildCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Create category
     */
    public function store(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền tạo danh mục.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        GuildCategory::create([
            'guild_id' => $guild->id,
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $guild->categories()->count(),
        ]);

        return redirect()->back()->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Update category
     */
    public function update(Request $request, $id, $categoryId)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa danh mục.');
        }

        $category = GuildCategory::where('id', $categoryId)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Delete category
     */
    public function destroy($id, $categoryId)
    {
        $guild = Guild::findOrFail($id);
        $user = Auth::user();
        
        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        
        $canManage = $user->isSuperAdmin() || $user->isAdmin();
        
        if (!$canManage && (!$userMembership || !$userMembership->canCreateCategories())) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa danh mục.');
        }

        $category = GuildCategory::where('id', $categoryId)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Xóa danh mục thành công!');
    }
}
