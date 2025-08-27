<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatisticsController extends Controller
{
    public function index()
    {
        // Fetch payment statistics data from the database
        $paymentStatistics = []; // Replace with actual data fetching logic

        return view('paymentStatistics.index', compact('paymentStatistics'));
    }
}
