<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bookings:expire-pending';

    /**
     * The console command description.
     */
    protected $description = 'Cancel pending bookings that are older than 15 minutes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredCount = Booking::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(15))
            ->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

        $this->info("Expired {$expiredCount} pending booking(s).");

        return self::SUCCESS;
    }
}
