@extends('layouts.app')

@section('title', 'Payment Details - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-credit-card"></i> Payment Details</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0 d-flex align-items-center">
            <i class="fas fa-receipt me-2"></i>
            Payment #{{ $payment->id }}
            <span class="badge ms-3 {{ $payment->status_badge_class }}">
                {{ $payment->status_label }}
            </span>
        </h3>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Payment Information -->
            <div class="col-md-6">
                <div class="mb-4">
                    <h4 class="h5 text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h4>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Amount</label>
                            <p class="form-control-plaintext h4 text-success">${{ number_format($payment->amount, 2) }}</p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Payment Date</label>
                            <p class="form-control-plaintext">{{ $payment->payment_date->format('F d, Y') }}</p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Payment Method</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-secondary">{{ $payment->payment_method_label }}</span>
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span>
                            </p>
                        </div>
                        
                        @if($payment->reference_number)
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Reference Number</label>
                            <p class="form-control-plaintext">{{ $payment->reference_number }}</p>
                        </div>
                        @endif
                        
                        @if($payment->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Notes</label>
                            <p class="form-control-plaintext">{{ $payment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="col-md-6">
                <div class="mb-4">
                    <h4 class="h5 text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-calendar-check me-2"></i>Booking Information
                    </h4>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Booking ID</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('bookings.show', $payment->booking) }}" class="text-decoration-none">
                                    #{{ $payment->booking->id }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Student</label>
                            <p class="form-control-plaintext">{{ $payment->booking->student->name ?? 'N/A' }}</p>
                            @if($payment->booking->student)
                                <small class="text-muted">{{ $payment->booking->student->email ?? '' }}</small>
                            @endif
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Room</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ $payment->booking->room->room_number ?? 'N/A' }}</span>
                                @if($payment->booking->room)
                                    <br><small class="text-muted">{{ ucfirst($payment->booking->room->type) }}</small>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Booking Period</label>
                            <p class="form-control-plaintext">
                                {{ $payment->booking->start_date->format('M d, Y') }} - 
                                @if($payment->booking->end_date)
                                    {{ $payment->booking->end_date->format('M d, Y') }}
                                @else
                                    Ongoing
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Booking Status</label>
                            <p class="form-control-plaintext">
                                @if($payment->booking->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($payment->booking->status == 'completed')
                                    <span class="badge bg-info">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="mt-4 pt-3 border-top">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Created</label>
                    <p class="form-control-plaintext">{{ $payment->created_at->format('F d, Y at g:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Last Updated</label>
                    <p class="form-control-plaintext">{{ $payment->updated_at->format('F d, Y at g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 pt-3 border-top">
            <div class="d-flex gap-2">
                @if($payment->status == 'pending')
                    <form action="{{ route('payments.complete', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn btn-success"
                                onclick="return confirm('Mark this payment as completed?')">
                            <i class="fas fa-check"></i> Mark as Completed
                        </button>
                    </form>
                    
                    <form action="{{ route('payments.fail', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn btn-danger"
                                onclick="return confirm('Mark this payment as failed?')">
                            <i class="fas fa-times"></i> Mark as Failed
                        </button>
                    </form>
                @endif
                
                @if($payment->status == 'completed')
                    <form action="{{ route('payments.refund', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn btn-info"
                                onclick="return confirm('Mark this payment as refunded?')">
                            <i class="fas fa-undo"></i> Mark as Refunded
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to move this payment to trash?')">
                        <i class="fas fa-trash"></i> Move to Trash
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
