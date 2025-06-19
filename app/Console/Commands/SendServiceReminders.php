<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerServices;
use App\Mail\ServiceExpiryReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendServiceReminders extends Command
{
    protected $signature = 'send:service-reminders';
    protected $description = 'Send email reminders for services expiring soon';

    public function handle()
    {
        $this->info('Starting to send service expiry reminders...');

        $expiringServices = CustomerServices::where('service_status', 'active')
            ->where('end_date', '>=', Carbon::today())
            ->where('end_date', '<=', Carbon::today()->addDays(7))
            ->with('user')
            ->get();

        if ($expiringServices->count() == 0) {
            $this->info('No services expiring in the next 7 days.');
            return;
        }

        $this->info("Found {$expiringServices->count()} services expiring soon.");

        foreach ($expiringServices as $service) {
            try {
                // Send using Mailable (much cleaner!)
                Mail::to($service->user->email)
                    ->send(new ServiceExpiryReminder($service));

                $this->info("Reminder sent for: {$service->service_name} - User: {$service->user->name}");
                
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for service: {$service->service_name}");
                $this->error("Error: " . $e->getMessage());
            }
        }

        $this->info('All reminders sent successfully!');
    }
}