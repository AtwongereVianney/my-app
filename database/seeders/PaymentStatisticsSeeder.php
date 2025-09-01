<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatistics;

class PaymentStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleData = [
            [
                'payment_id' => 1,
                'amount' => 150.00,
                'currency' => 'USD',
                'status' => 'completed',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'payment_id' => 2,
                'amount' => 75.50,
                'currency' => 'EUR',
                'status' => 'completed',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'payment_id' => 3,
                'amount' => 200.00,
                'currency' => 'USD',
                'status' => 'failed',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'payment_id' => 4,
                'amount' => 125.75,
                'currency' => 'GBP',
                'status' => 'pending',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'payment_id' => 5,
                'amount' => 300.00,
                'currency' => 'USD',
                'status' => 'refunded',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 6,
                'amount' => 89.99,
                'currency' => 'EUR',
                'status' => 'completed',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'payment_id' => 7,
                'amount' => 175.25,
                'currency' => 'USD',
                'status' => 'completed',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'payment_id' => 8,
                'amount' => 50.00,
                'currency' => 'GBP',
                'status' => 'failed',
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ],
        ];

        foreach ($sampleData as $data) {
            PaymentStatistics::create($data);
        }
    }
}
