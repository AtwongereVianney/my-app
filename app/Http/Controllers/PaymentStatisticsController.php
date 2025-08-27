<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentStatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $paymentStatus = $request->get('payment_status');

        // Build base query
        $paymentsQuery = DB::table('payments')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        if ($paymentStatus) {
            $paymentsQuery->where('status', $paymentStatus);
        }

        // Summary Statistics
        $totalRevenue = $paymentsQuery->clone()
            ->where('status', 'completed')
            ->sum('amount');

        $completedPayments = $paymentsQuery->clone()
            ->where('status', 'completed')
            ->count();

        $pendingPayments = $paymentsQuery->clone()
            ->where('status', 'pending')
            ->count();

        $averagePayment = $completedPayments > 0 
            ? $totalRevenue / $completedPayments 
            : 0;

        // Chart Data - Revenue Trend (last 6 months)
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->startOfMonth()->format('Y-m-d');
            $monthEnd = $date->endOfMonth()->format('Y-m-d');
            
            $monthlyRevenue = DB::table('payments')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');
                
            $chartData[] = (float) $monthlyRevenue;
            $chartLabels[] = $date->format('M Y');
        }

        // Status Distribution
        $statusData = [
            DB::table('payments')->whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'completed')->count(),
            DB::table('payments')->whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'pending')->count(),
            DB::table('payments')->whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'failed')->count(),
            DB::table('payments')->whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'refunded')->count(),
        ];

        // Payment Methods Statistics
        $paymentMethods = DB::table('payments')
            ->select(
                'payment_method as method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total'),
                DB::raw('(SUM(amount) / (SELECT SUM(amount) FROM payments WHERE created_at BETWEEN ? AND ? AND status = "completed")) * 100 as percentage')
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->setBindings([$dateFrom, $dateTo, $dateFrom, $dateTo])
            ->get()
            ->map(function($item) {
                return [
                    'method' => $item->method,
                    'count' => $item->count,
                    'total' => (float) $item->total,
                    'percentage' => (float) $item->percentage ?? 0
                ];
            });

        // Top Students by Payment Volume
        $topStudents = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->select(
                'students.name',
                'students.email',
                DB::raw('SUM(payments.amount) as total_paid'),
                DB::raw('COUNT(payments.id) as payment_count')
            )
            ->whereBetween('payments.created_at', [$dateFrom, $dateTo])
            ->where('payments.status', 'completed')
            ->groupBy('students.id', 'students.name', 'students.email')
            ->orderBy('total_paid', 'desc')
            ->limit(10)
            ->get()
            ->map(function($student) {
                return [
                    'name' => $student->name,
                    'email' => $student->email,
                    'total_paid' => (float) $student->total_paid,
                    'payment_count' => $student->payment_count
                ];
            });

        // Recent Transactions
        $recentTransactions = DB::table('payments')
            ->join('students', 'payments.student_id', '=', 'students.id')
            ->select(
                'payments.id',
                'payments.amount',
                'payments.payment_method',
                'payments.status',
                'payments.created_at',
                'students.name as student_name',
                'students.email as student_email'
            )
            ->whereBetween('payments.created_at', [$dateFrom, $dateTo])
            ->orderBy('payments.created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => (float) $transaction->amount,
                    'payment_method' => $transaction->payment_method,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at,
                    'student_name' => $transaction->student_name,
                    'student_email' => $transaction->student_email,
                    'student_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($transaction->student_name) . '&background=4e73df&color=fff'
                ];
            });

        return view('paymentStatistics.index', compact(
            'totalRevenue',
            'completedPayments',
            'pendingPayments',
            'averagePayment',
            'chartData',
            'chartLabels',
            'statusData',
            'paymentMethods',
            'topStudents',
            'recentTransactions'
        ));
    }

    public function export(Request $request)
    {
        // Implement export functionality here
        // This could export to CSV, Excel, or PDF
        return response()->json(['message' => 'Export functionality to be implemented']);
    }
}