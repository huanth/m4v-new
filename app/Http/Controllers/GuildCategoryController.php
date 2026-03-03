<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Added Gate
use App\Models\Guild;
use App\Models\GuildCategory;
use App\Services\GuildCategoryService;

class GuildCategoryController extends Controller
{
    protected GuildCategoryService $guildCategoryService;

    public function __construct(GuildCategoryService $guildCategoryService)
    {
        $this->middleware('auth');
        $this->guildCategoryService = $guildCategoryService;
    }

    /**
     * Create category
     */
    public function store(Request $request, $id)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('create', [GuildCategory::class, $guild]); // Replaced manual check

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $this->guildCategoryService->createCategory($guild->id, $request->all());

        return redirect()->back()->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Update category
     */
    public function update(Request $request, $id, $categoryId)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('update', [GuildCategory::class, $guild]); // Replaced manual check

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

        $this->guildCategoryService->updateCategory($category, $request->all());

        return redirect()->back()->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Delete category
     */
    public function destroy($id, $categoryId)
    {
        $guild = Guild::findOrFail($id);

        Gate::authorize('delete', [GuildCategory::class, $guild]); // Replaced manual check

        $category = GuildCategory::where('id', $categoryId)
            ->where('guild_id', $guild->id)
            ->first();

        if (!$category) {
            return redirect()->back()->with('error', 'Danh mục không tồn tại.');
        }

        $this->guildCategoryService->deleteCategory($category);

        return redirect()->back()->with('success', 'Xóa danh mục thành công!');
    }
}
