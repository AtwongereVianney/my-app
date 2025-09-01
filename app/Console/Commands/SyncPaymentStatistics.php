<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\PaymentStatistics;

class SyncPaymentStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync-statistics {--user-id= : Sync statistics for a specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payment statistics from real payment data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        
        if ($userId) {
            $this->info("Starting payment statistics sync for user ID: {$userId}...");
            
            // Get all payments for the specific user that don't have corresponding statistics
            $payments = Payment::whereHas('booking.student', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('payment_statistics')
                      ->whereRaw('payment_statistics.payment_id = payments.id');
            })->get();
        } else {
            $this->info('Starting payment statistics sync for all users...');
            
            // Get all payments that don't have corresponding statistics
            $payments = Payment::whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                      ->from('payment_statistics')
                      ->whereRaw('payment_statistics.payment_id = payments.id');
            })->get();
        }
        
        $count = 0;
        foreach ($payments as $payment) {
            PaymentStatistics::create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => 'USD', // Default currency
                'status' => $payment->status,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]);
            $count++;
        }
        
        if ($userId) {
            $this->info("Synced {$count} payment statistics for user ID: {$userId}");
        } else {
            $this->info("Synced {$count} payment statistics for all users.");
        }
        
        return 0;
    }
}
