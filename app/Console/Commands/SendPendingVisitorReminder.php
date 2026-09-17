<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visitor;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Services\FirebaseService;
use Carbon\Carbon;

class SendPendingVisitorReminder extends Command
{
    protected $signature = 'visitor:pending-reminder';

    protected $description = 'Send reminder notification for pending visitors';

    public function handle()
    {
        // Visitors pending for 15+ minutes
        $visitors = Visitor::where('visit_status', 0)
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->where(function ($q) {
                $q->whereNull('last_reminder_at')
                    ->orWhere('last_reminder_at', '<=', now()->subMinutes(15));
            })
            ->get();

        $firebase = app(FirebaseService::class);

        foreach ($visitors as $visitor) {

            $meetUser = User::where('email', $visitor->meet_person_email)->first();

            if (!$meetUser) {
                continue;
            }

            $title = 'Visitor Waiting Approval';

            $message = $visitor->name .
                ' is still waiting for your approval.';

            // Save notification
            $notification = Notification::create([
                'user_id' => $meetUser->id,
                'visitor_id' => $visitor->id,
                'title' => $title,
                'message' => $message,
                'status' => 0,
            ]);

            // Single device token
            $device = UserDevice::where('user_id', $meetUser->id)
                ->where('is_active', 1)
                ->first();

            if ($device) {

                $sent = $firebase->send(
                    $device->device_token,
                    $title,
                    $message
                );

                if ($sent) {
                    $notification->update([
                        'status' => 1
                    ]);

                    $visitor->update([
                        'last_reminder_at' => now(),
                        'reminder_count' => $visitor->reminder_count + 1,
                    ]);
                }
            }
        }

        $this->info('Pending visitor reminders sent successfully.');
    }
}