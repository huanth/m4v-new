<?php

namespace App\Services;

use App\Models\GuildCategory;

class GuildCategoryService
{
    /**
     * Create a category
     */
    public function createCategory(int $guildId, array $data): GuildCategory
    {
        return GuildCategory::create([
            'guild_id' => $guildId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'sort_order' => GuildCategory::where('guild_id', $guildId)->count(),
        ]);
    }

    /**
     * Update a category
     */
    public function updateCategory(GuildCategory $category, array $data): bool
    {
        return $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Delete a category
     */
    public function deleteCategory(GuildCategory $category): bool
    {
        return $category->delete();
    }
}
