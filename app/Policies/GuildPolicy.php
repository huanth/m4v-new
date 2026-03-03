<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GuildPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canCreateGuild() && !$user->isInGuild();
    }

    /**
     * Determine whether the user can manage the guild.
     */
    public function manage(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles();
    }

    /**
     * Determine whether the user can update the banner.
     */
    public function updateBanner(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->isLeader();
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function updateAnnouncement(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $membership = $guild->members()->where('user_id', $user->id)->first();
        return $membership && $membership->canManageRoles();
    }
}
