<?php

namespace App\Services;

use App\Models\Guild;
use App\Models\GuildBan;
use App\Models\GuildMember;

class GuildMemberService
{
    /**
     * User joins a guild
     */
    public function joinGuild(Guild $guild, int $userId): GuildMember
    {
        return GuildMember::create([
            'guild_id' => $guild->id,
            'user_id' => $userId,
            'role' => Guild::ROLE_MEMBER,
        ]);
    }

    /**
     * User leaves a guild
     */
    public function leaveGuild(Guild $guild, int $userId): bool
    {
        $membership = $guild->members()->where('user_id', $userId)->first();
        
        if ($membership) {
            return $membership->delete();
        }

        return false;
    }

    /**
     * Update member's role
     */
    public function updateRole(GuildMember $member, string $role): bool
    {
        return $member->update(['role' => $role]);
    }

    /**
     * Ban a member from the guild: removes their membership and records the ban
     */
    public function banMember(Guild $guild, int $targetUserId, int $bannedByUserId, ?string $reason = null): GuildBan
    {
        $guild->members()->where('user_id', $targetUserId)->delete();

        return GuildBan::create([
            'guild_id' => $guild->id,
            'user_id' => $targetUserId,
            'banned_by' => $bannedByUserId,
            'reason' => $reason,
        ]);
    }

    /**
     * Lift a guild ban
     */
    public function unbanMember(Guild $guild, int $targetUserId): bool
    {
        return GuildBan::where('guild_id', $guild->id)
            ->where('user_id', $targetUserId)
            ->active()
            ->update(['is_active' => false]) > 0;
    }
}
