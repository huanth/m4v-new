<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Any authenticated (non-banned) user can file a report.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only moderators and above can view the report queue.
     */
    public function viewAny(User $user): bool
    {
        return $user->isModerator();
    }

    /**
     * Only moderators and above can resolve/dismiss a report.
     */
    public function review(User $user, Report $report): bool
    {
        return $user->isModerator();
    }
}
