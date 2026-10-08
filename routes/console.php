<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
Use App\Models\CronJobs;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');




// Send service expiry reminders every day at 9:00 AM

Schedule::command('send:service-reminders')
    ->dailyAt('05:00')
    ->timezone('Asia/Karachi')
    ->before(function () {
        Log::info('Running: send:service-reminders (before execution)');
    })
    ->onSuccess(function () {
        try {
            CronJobs::create([
                'job_name'      => 'send:service-reminders',
                'status'        => 'success',
                'last_run_time' => now(),
                'failed_reason' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('cron_jobs insert failed: ' . $e->getMessage());
        }
    })
    ->onFailure(function () {
        try {
            CronJobs::create([
                'job_name'      => 'send:service-reminders',
                'status'        => 'failed',
                'last_run_time' => now(),
                'failed_reason' => 'An error occurred while running the command.',
            ]);
        } catch (\Throwable $e) {
            Log::error('cron_jobs insert failed: ' . $e->getMessage());
        }
    })
    ->after(function () {
        Log::info('Completed: send:service-reminders (after execution)');
    });