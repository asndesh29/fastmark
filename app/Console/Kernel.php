<?php

function schedule(Schedule $schedule)
{
    $schedule->command('renewals:send-reminders')->daily();
}