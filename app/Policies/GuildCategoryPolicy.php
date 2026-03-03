<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\GuildCategory;
use App\Models\User;

class GuildCategoryPolicy
{
    /**
     * Determine whether the user can create categories in the guild.
     */
    public function create(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canCreateCategories();
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Guild $guild): bool
    {
        return $this->create($user, $guild);
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, Guild $guild): bool
    {
        return $this->create($user, $guild);
    }
}
