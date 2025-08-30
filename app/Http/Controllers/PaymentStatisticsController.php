<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PaymentStatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = DB::table('payment_statistics');
        
        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get paginated payment statistics for the table
        $paymentStatistics = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Calculate statistics
        $statistics = $this->calculateStatistics();
        
        return view('payment-statistics', compact('paymentStatistics', 'statistics'));
    }
    
    private function calculateStatistics()
    {
        // Get all payment statistics
        $allPayments = DB::table('payment_statistics')->get();
        
        if ($allPayments->isEmpty()) {
            return [
                'total_amount' => 0,
                'total_count' => 0,
                'success_rate' => 0,
                'average_amount' => 0,
                'primary_currency' => 'USD',
                'status_breakdown' => [],
                'currency_breakdown' => [],
                'monthly_data' => [],
                'available_currencies' => []
            ];
        }
        
        // Basic calculations
        $totalAmount = $allPayments->sum('amount');
        $totalCount = $allPayments->count();
        $completedCount = $allPayments->where('status', 'completed')->count();
        $successRate = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        
        // Get primary currency (most used)
        $currencyGroups = $allPayments->groupBy('currency');
        $primaryCurrency = $currencyGroups->sortByDesc(function ($group) {
            return $group->count();
        })->keys()->first() ?? 'USD';
        
        // Status breakdown
        $statusBreakdown = [];
        $statusGroups = $allPayments->groupBy('status');
        foreach (['pending', 'completed', 'failed', 'refunded'] as $status) {
            $count = $statusGroups->get($status, collect())->count();
            $percentage = $totalCount > 0 ? ($count / $totalCount) * 100 : 0;
            
            if ($count > 0) {
                $statusBreakdown[$status] = [
                    'count' => $count,
                    'percentage' => $percentage
                ];
            }
        }
        
        // Currency breakdown
        $currencyBreakdown = [];
        foreach ($currencyGroups as $currency => $payments) {
            $count = $payments->count();
            $amount = $payments->sum('amount');
            $percentage = $totalCount > 0 ? ($count / $totalCount) * 100 : 0;
            
            $currencyBreakdown[$currency] = [
                'count' => $count,
                'total_amount' => $amount,
                'percentage' => $percentage
            ];
        }
        
        // Monthly data (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('F Y');
            
            $monthlyPayments = $allPayments->filter(function ($payment) use ($date) {
                $paymentDate = Carbon::parse($payment->created_at);
                return $paymentDate->year === $date->year && $paymentDate->month === $date->month;
            });
            
            if ($monthlyPayments->isNotEmpty()) {
                $monthlyData[$monthKey] = [
                    'amount' => $monthlyPayments->sum('amount'),
                    'count' => $monthlyPayments->count()
                ];
            }
        }
        
        // Available currencies
        $availableCurrencies = $allPayments->pluck('currency')->unique()->sort()->values()->toArray();
        
        return [
            'total_amount' => $totalAmount,
            'total_count' => $totalCount,
            'success_rate' => $successRate,
            'average_amount' => $averageAmount,
            'primary_currency' => $primaryCurrency,
            'status_breakdown' => $statusBreakdown,
            'currency_breakdown' => $currencyBreakdown,
            'monthly_data' => $monthlyData,
            'available_currencies' => $availableCurrencies
        ];
    }
    
    public function export()
    {
        // Export functionality - you can implement CSV/Excel export here
        $payments = DB::table('payment_statistics')->get();
        
        $filename = 'payment_statistics_' . date('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Payment ID', 'Amount', 'Currency', 'Status', 'Created At']);
            
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_id,
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    $payment->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}