<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatistics extends Model
{
    use HasFactory;

    protected $table = 'payment_statistics';

    protected $fillable = [
        'payment_id',
        'amount',
        'currency',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
