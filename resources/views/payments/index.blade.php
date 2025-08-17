@extends('layouts.app')

@section('title', 'Payments - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-credit-card"></i> Payments</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Record Payment
        </a>
        <a href="{{ route('payments.statistics') }}" class="btn btn-info">
            <i class="fas fa-chart-bar"></i> Statistics
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
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Room</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Reference</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <strong>#{{ $payment->id }}</strong>
                            </td>
                            <td>
                                <strong>{{ $payment->booking->student->name ?? 'N/A' }}</strong>
                                @if($payment->booking->student)
                                    <br><small class="text-muted">{{ $payment->booking->student->email ?? '' }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $payment->booking->room->room_number ?? 'N/A' }}</span>
                                @if($payment->booking->room)
                                    <br><small class="text-muted">{{ ucfirst($payment->booking->room->type) }}</small>
                                @endif
                            </td>
                            <td>
                                <strong>${{ number_format($payment->amount, 2) }}</strong>
                            </td>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $payment->payment_method_label }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span>
                            </td>
                            <td>
                                @if($payment->reference_number)
                                    <small class="text-muted">{{ $payment->reference_number }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($payment->status == 'pending')
                                        <form action="{{ route('payments.complete', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    onclick="return confirm('Mark this payment as completed?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('payments.fail', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Mark this payment as failed?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($payment->status == 'completed')
                                        <form action="{{ route('payments.refund', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-info" 
                                                    onclick="return confirm('Mark this payment as refunded?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to move this payment to trash?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($payments->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $payments->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No payments found</h4>
                <p class="text-muted">Start by recording your first payment.</p>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Record First Payment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
