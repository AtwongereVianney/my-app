<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatisticsController extends Controller
{
    public function index()
    {
        // Fetch payment statistics data from the database
        $paymentStatistics = [
    'total_amount' => $totalAmount,
    'total_count' => $totalCount,
    'success_rate' => $successRate,
    'average_amount' => $averageAmount,
    'primary_currency' => 'USD',
    'status_breakdown' => [
        'completed' => ['count' => 50, 'percentage' => 70],
        'pending' => ['count' => 15, 'percentage' => 20],
        // etc...
    ],
    'currency_breakdown' => [
        'USD' => ['count' => 100, 'total_amount' => 5000, 'percentage' => 80],
        // etc...
    ],
    'monthly_data' => [
        'January 2024' => ['amount' => 1000, 'count' => 10],
        // etc...
    ],
    'available_currencies' => ['USD', 'EUR', 'GBP']
];

        return view('paymentStatistics.index', compact('paymentStatistics'));
    }
}
