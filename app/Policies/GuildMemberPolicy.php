<?php

namespace App\Policies;

use App\Models\Guild;
use App\Models\GuildMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GuildMemberPolicy
{
    /**
     * Determine whether the user can join the guild.
     */
    public function join(User $user, Guild $guild): bool
    {
        if ($guild->hasMember($user->id)) {
            return false;
        }

        if (!$guild->is_active) {
            return false;
        }

        return $guild->canUserJoin($user->id);
    }

    /**
     * Determine whether the user can leave the guild.
     */
    public function leave(User $user, Guild $guild): bool
    {
        $membership = $guild->members()->where('user_id', $user->id)->first();
        
        if (!$membership) {
            return false;
        }

        return !$membership->isLeader();
    }

    /**
     * Determine whether the user can update a member's role.
     */
    public function updateRole(User $user, Guild $guild, GuildMember $targetMember): bool
    {
        if ($targetMember->isLeader()) {
            return false;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        return $userMembership && $userMembership->canManageRoles();
    }

    /**
     * Determine whether the user can ban a member from the guild.
     */
    public function ban(User $user, Guild $guild, GuildMember $targetMember): bool
    {
        if ($targetMember->isLeader()) {
            return false;
        }

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        return $userMembership && $userMembership->canManageRoles();
    }

    /**
     * Determine whether the user can unban a member / view the guild ban list.
     */
    public function unban(User $user, Guild $guild): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        $userMembership = $guild->members()->where('user_id', $user->id)->first();
        return $userMembership && $userMembership->canManageRoles();
    }
}
