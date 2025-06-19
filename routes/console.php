<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');




// Send service expiry reminders every day at 9:00 AM
Schedule::command('send:service-reminders')
    ->dailyAt('09:00') // Morning 9:00 AM
    // also log
    ->timezone('Asia/Karachi')
    ->before(function (){
        Log::info('Running: send:service-reminders (before execution)');
    })
    ->after(function (){
        Log::info('Completed: send:service-reminders (after execution)');
    });
    


