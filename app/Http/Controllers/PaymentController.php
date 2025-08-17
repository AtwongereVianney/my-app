<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Student;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['booking.student', 'booking.room'])
            ->latest()
            ->paginate(15);
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookings = Booking::with(['student', 'room'])
            ->where('status', 'active')
            ->get();
        
        return view('payments.create', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,mobile_money,check,other',
            'status' => 'required|in:pending,completed,failed,refunded',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.student', 'booking.room']);
        
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $bookings = Booking::with(['student', 'room'])->get();
        
        return view('payments.edit', compact('payment', 'bookings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,mobile_money,check,other',
            'status' => 'required|in:pending,completed,failed,refunded',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete(); // This will use soft delete

        return redirect()->route('payments.index')
            ->with('success', 'Payment moved to trash successfully.');
    }

    /**
     * Mark payment as completed.
     */
    public function complete(Payment $payment)
    {
        $payment->update(['status' => 'completed']);

        return redirect()->back()
            ->with('success', 'Payment marked as completed.');
    }

    /**
     * Mark payment as failed.
     */
    public function fail(Payment $payment)
    {
        $payment->update(['status' => 'failed']);

        return redirect()->back()
            ->with('success', 'Payment marked as failed.');
    }

    /**
     * Mark payment as refunded.
     */
    public function refund(Payment $payment)
    {
        $payment->update(['status' => 'refunded']);

        return redirect()->back()
            ->with('success', 'Payment marked as refunded.');
    }

    /**
     * Restore a soft-deleted payment.
     */
    public function restore($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        $payment->restore();

        return redirect()->route('payments.index')
            ->with('success', 'Payment restored successfully.');
    }

    /**
     * Permanently delete a payment.
     */
    public function forceDelete($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        $payment->forceDelete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment permanently deleted.');
    }

    /**
     * Show trashed payments.
     */
    public function trashed()
    {
        $payments = Payment::onlyTrashed()
            ->with(['booking.student', 'booking.room'])
            ->latest()
            ->paginate(15);
        
        return view('payments.trashed', compact('payments'));
    }

    /**
     * Show payments for a specific booking.
     */
    public function bookingPayments(Booking $booking)
    {
        $payments = $booking->payments()->latest()->paginate(10);
        
        return view('payments.booking-payments', compact('payments', 'booking'));
    }

    /**
     * Show payment statistics.
     */
    public function statistics()
    {
        $totalPayments = Payment::count();
        $completedPayments = Payment::completed()->count();
        $pendingPayments = Payment::pending()->count();
        $failedPayments = Payment::failed()->count();
        
        $totalAmount = Payment::completed()->sum('amount');
        $pendingAmount = Payment::pending()->sum('amount');
        
        $recentPayments = Payment::with(['booking.student', 'booking.room'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('payments.statistics', compact(
            'totalPayments',
            'completedPayments',
            'pendingPayments',
            'failedPayments',
            'totalAmount',
            'pendingAmount',
            'recentPayments'
        ));
    }
}
