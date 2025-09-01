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
    protected $signature = 'payments:sync-statistics';

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
        $this->info('Starting payment statistics sync...');
        
        // Get all payments that don't have corresponding statistics
        $payments = Payment::whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('payment_statistics')
                  ->whereRaw('payment_statistics.payment_id = payments.id');
        })->get();
        
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
        
        $this->info("Synced {$count} payment statistics from real payment data.");
        
        return 0;
    }
}
