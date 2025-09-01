<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function student()
    {
        return $this->hasOneThrough(Student::class, Booking::class, 'id', 'id', 'booking_id', 'student_id');
    }

    public function room()
    {
        return $this->hasOneThrough(Room::class, Booking::class, 'id', 'id', 'booking_id', 'room_id');
    }

    public function statistics()
    {
        return $this->hasOne(PaymentStatistics::class);
    }

    // Payment method labels
    public static function getPaymentMethodLabels()
    {
        return [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'mobile_money' => 'Mobile Money',
            'check' => 'Check',
            'other' => 'Other',
        ];
    }

    // Status labels
    public static function getStatusLabels()
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];
    }

    // Get payment method label
    public function getPaymentMethodLabelAttribute()
    {
        return self::getPaymentMethodLabels()[$this->payment_method] ?? $this->payment_method;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        return [
            'pending' => 'bg-warning',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'refunded' => 'bg-info',
        ][$this->status] ?? 'bg-secondary';
    }

    // Scope for completed payments
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for failed payments
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
