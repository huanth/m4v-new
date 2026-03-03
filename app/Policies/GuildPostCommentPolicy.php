<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\GuildPostComment;
use App\Models\User;

class GuildPostCommentPolicy
{
    /**
     * Determine whether the user can create comments in the guild.
     */
    public function create(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        return $guild->hasMember($user->id);
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, GuildPostComment $comment): bool
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $comment->post->guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles(); // Phó bang trở lên được sửa
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, GuildPostComment $comment): bool
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $comment->post->guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles();
    }
}
