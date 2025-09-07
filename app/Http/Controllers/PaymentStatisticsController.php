<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentStatistics;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;

class PaymentStatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Sync payment statistics from real payment data for the authenticated user
        $this->syncPaymentStatistics();
        
        // Base query using Eloquent - filter by authenticated user
        $query = PaymentStatistics::whereHas('payment.booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        });
        
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
        
        // Calculate statistics for the authenticated user only (cached)
        $statistics = Cache::remember($this->getStatsCacheKey(), now()->addMinutes(10), function () {
            return $this->calculateStatistics();
        });
        
        return view('paymentStatistics.index', compact('paymentStatistics', 'statistics'));
    }
    
    private function syncPaymentStatistics()
    {
        // Get all payments for the authenticated user that don't have corresponding statistics
        $payments = Payment::whereHas('booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('payment_statistics')
                  ->whereRaw('payment_statistics.payment_id = payments.id');
        })->get();
        
        foreach ($payments as $payment) {
            PaymentStatistics::create([
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => 'USD', // Default currency, you can modify this based on your needs
                'status' => $payment->status,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ]);
        }
    }
    
    private function calculateStatistics()
    {
        // Get all payment statistics for the authenticated user only
        $allPayments = PaymentStatistics::whereHas('payment.booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        
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
        
        // Monthly data (last 6 months) - using PHP date functions instead of Carbon
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $targetDate = date('Y-m-01', strtotime("-{$i} months"));
            $monthKey = date('F Y', strtotime($targetDate));
            $year = date('Y', strtotime($targetDate));
            $month = date('m', strtotime($targetDate));
            
            $monthlyPayments = $allPayments->filter(function ($payment) use ($year, $month) {
                $paymentDate = strtotime($payment->created_at);
                return date('Y', $paymentDate) == $year && date('m', $paymentDate) == $month;
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

    private function getStatsCacheKey(): string
    {
        return 'payment_stats_summary_user_' . auth()->id();
    }
    
    public function export()
    {
        // Sync payment statistics from real payment data for the authenticated user
        $this->syncPaymentStatistics();
        
        // Export functionality using Eloquent - filter by authenticated user
        $payments = PaymentStatistics::whereHas('payment.booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        
        $filename = 'payment_statistics_' . auth()->user()->name . '_' . date('Y_m_d_H_i_s') . '.csv';
        
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

    public function create()
    {
        return view('paymentStatistics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|integer|exists:payments,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        // Ensure the payment belongs to the authenticated user
        $payment = Payment::whereHas('booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($request->payment_id);

        PaymentStatistics::create($request->all());

        // Invalidate cached statistics for this user
        Cache::forget($this->getStatsCacheKey());

        return redirect()->route('paymentStatistics.index')
            ->with('success', 'Payment statistic created successfully.');
    }

    public function show(PaymentStatistics $payment_statistic)
    {
        // Ensure the payment statistic belongs to the authenticated user
        $this->authorizePaymentStatistic($payment_statistic);
        
        return view('paymentStatistics.show', compact('payment_statistic'));
    }

    public function edit(PaymentStatistics $payment_statistic)
    {
        // Ensure the payment statistic belongs to the authenticated user
        $this->authorizePaymentStatistic($payment_statistic);
        
        return view('paymentStatistics.edit', compact('payment_statistic'));
    }

    public function update(Request $request, PaymentStatistics $payment_statistic)
    {
        // Ensure the payment statistic belongs to the authenticated user
        $this->authorizePaymentStatistic($payment_statistic);
        
        $request->validate([
            'payment_id' => 'required|integer|exists:payments,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);

        // Ensure the new payment also belongs to the authenticated user
        $payment = Payment::whereHas('booking.student', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($request->payment_id);

        $payment_statistic->update($request->all());

        // Invalidate cached statistics for this user
        Cache::forget($this->getStatsCacheKey());

        return redirect()->route('paymentStatistics.index')
            ->with('success', 'Payment statistic updated successfully.');
    }

    public function destroy(PaymentStatistics $payment_statistic)
    {
        // Ensure the payment statistic belongs to the authenticated user
        $this->authorizePaymentStatistic($payment_statistic);
        
        $payment_statistic->delete();

        // Invalidate cached statistics for this user
        Cache::forget($this->getStatsCacheKey());

        return redirect()->route('paymentStatistics.index')
            ->with('success', 'Payment statistic deleted successfully.');
    }

    private function authorizePaymentStatistic(PaymentStatistics $payment_statistic)
    {
        $userOwnsStatistic = $payment_statistic->payment->booking->student->user_id === auth()->id();
        
        if (!$userOwnsStatistic) {
            abort(403, 'You are not authorized to access this payment statistic.');
        }
    }
}