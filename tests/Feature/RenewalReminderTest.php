<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\RoadPermit;
use App\Models\Renewal;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RenewalReminderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RenewalReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_renewal_reminder_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);
        $roadPermit = RoadPermit::factory()->create(['vehicle_id' => $vehicle->id]);

        $renewal = Renewal::create([
            'vehicle_id' => $vehicle->id,
            'renewable_type' => RoadPermit::class,
            'renewable_id' => $roadPermit->id,
            'status' => 'pending',
            'start_date' => now()->subDays(30),
            'expiry_date' => now()->addDays(7),
            'reminder_date' => now(),
        ]);

        $this->artisan('renewals:send-reminders')->assertSuccessful();

        Notification::assertSentTo($user, RenewalReminderNotification::class);
    }
}


// php artisan test

// php artisan test --filter=RenewalReminderTest
