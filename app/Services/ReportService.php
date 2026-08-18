<?php

namespace App\Services;

use App\Models\Report;

class ReportService
{
    /**
     * Create a new report
     */
    public function createReport(int $reporterId, string $reportableType, int $reportableId, string $reason): Report
    {
        return Report::create([
            'reporter_id' => $reporterId,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
            'reason' => $reason,
        ]);
    }

    /**
     * Mark a report as resolved (action taken)
     */
    public function resolve(Report $report, int $reviewerId, ?string $note = null): bool
    {
        return $report->update([
            'status' => Report::STATUS_RESOLVED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    /**
     * Dismiss a report (no action needed)
     */
    public function dismiss(Report $report, int $reviewerId, ?string $note = null): bool
    {
        return $report->update([
            'status' => Report::STATUS_DISMISSED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }
}
