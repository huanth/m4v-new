<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\GuildPost;
use App\Models\User;

class GuildPostPolicy
{
    /**
     * Determine whether the user can create posts in the guild.
     */
    public function create(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        return $guild->hasMember($user->id);
    }

    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, GuildPost $post): bool
    {
        if ($user->id === $post->author_id) {
            return true;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $post->guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles(); // Phó bang trở lên được sửa
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, GuildPost $post): bool
    {
        if ($user->id === $post->author_id) {
            return true;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $post->guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles();
    }

    /**
     * Determine whether the user can pin the post.
     */
    public function pin(User $user, GuildPost $post): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $post->guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles();
    }

    /**
     * Determine whether the user can lock the post.
     */
    public function lock(User $user, GuildPost $post): bool
    {
        return $this->pin($user, $post);
    }
}
