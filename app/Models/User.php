<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Student::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Booking::class, 'student_id', 'booking_id', 'id', 'id');
    }

    public function paymentStatistics()
    {
        return $this->hasManyThrough(PaymentStatistics::class, Payment::class, 'booking_id', 'payment_id', 'id', 'id');
    }
}
