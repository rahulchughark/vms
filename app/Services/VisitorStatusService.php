<?php

namespace App\Services;

class VisitorStatusService
{
    /**
     * Map visit status integer to business label.
     */
    public function label(int $visitStatus): string
    {
        return match ($visitStatus) {
            0 => 'Pending',
            1 => 'Approve',
            2 => 'Reject',
            3 => 'Completed',
            4 => 'In Progress',
            default => 'Unknown',
        };
    }
}
