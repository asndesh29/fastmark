<?php

namespace App\Console\Commands;

use App\Models\Renewal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-renewal-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming vehicle renewals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $renewals = Renewal::whereDate('reminder_date', $today)
            ->with('vehicle.user')
            ->get();

        foreach ($renewals as $renewal) {
            $user = $renewal->vehicle->user;
            if ($user) {
                $user->notify(new RenewalReminderNotification($renewal));
                $this->info("Reminder sent to {$user->email} for renewal ID: {$renewal->id}");
            }
        }

        return Command::SUCCESS;
    }
}
