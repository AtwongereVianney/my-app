<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\PaymentStatistics;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        // Create corresponding payment statistics
        PaymentStatistics::create([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => 'USD', // Default currency
            'status' => $payment->status,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ]);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Update corresponding payment statistics
        $statistics = PaymentStatistics::where('payment_id', $payment->id)->first();
        
        if ($statistics) {
            $statistics->update([
                'amount' => $payment->amount,
                'status' => $payment->status,
                'updated_at' => $payment->updated_at,
            ]);
        } else {
            // If statistics don't exist, create them
            PaymentStatistics::create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => 'USD',
                'status' => $payment->status,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]);
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        // Delete corresponding payment statistics
        PaymentStatistics::where('payment_id', $payment->id)->delete();
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        // Recreate corresponding payment statistics
        PaymentStatistics::create([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => 'USD',
            'status' => $payment->status,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ]);
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        // Delete corresponding payment statistics
        PaymentStatistics::where('payment_id', $payment->id)->forceDelete();
    }
}
