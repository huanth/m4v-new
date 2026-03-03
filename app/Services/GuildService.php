<?php

namespace App\Services;

use App\Models\Guild;
use App\Models\GuildMember;
use Illuminate\Support\Facades\Storage;

class GuildService
{
    /**
     * Create a new guild and set the creator as leader
     */
    public function createGuild(array $data, int $userId): Guild
    {
        $guild = Guild::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'leader_id' => $userId,
            'max_members' => 999999, // Không giới hạn
        ]);

        GuildMember::create([
            'guild_id' => $guild->id,
            'user_id' => $userId,
            'role' => Guild::ROLE_LEADER,
        ]);

        return $guild;
    }

    /**
     * Update the guild's banner image
     */
    public function updateBanner(Guild $guild, $bannerFile): bool
    {
        if ($guild->banner && Storage::disk('public')->exists($guild->banner)) {
            Storage::disk('public')->delete($guild->banner);
        }

        $path = $bannerFile->store('guild-banners', 'public');
        return $guild->update(['banner' => $path]);
    }

    /**
     * Update the guild's announcement
     */
    public function updateAnnouncement(Guild $guild, ?string $announcement): bool
    {
        return $guild->update(['announcement' => $announcement]);
    }
}
