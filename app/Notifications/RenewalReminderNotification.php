<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalReminderNotification extends Notification
{
    public $renewal;

    public function __construct($renewal)
    {
        $this->renewal = $renewal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Vehicle Renewal Reminder')
            ->line("Your vehicle renewal ({$this->renewal->renewable_type}) is due on {$this->renewal->expiry_date}.")
            ->action('View Renewal', url('/renewals/' . $this->renewal->id))
            ->line('Please complete your renewal on time to avoid penalties.');
    }
}

