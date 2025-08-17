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
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" data-bs-target="#completeModal{{ $payment->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#failModal{{ $payment->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    @if($payment->status == 'completed')
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" data-bs-target="#refundModal{{ $payment->id }}">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $payment->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
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

<!-- Action Modals -->
@foreach($payments as $payment)
    <!-- Complete Payment Modal -->
    @if($payment->status == 'pending')
    <div class="modal fade" id="completeModal{{ $payment->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeModalLabel{{ $payment->id }}">
                        <i class="fas fa-check text-success"></i> Complete Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark this payment as <strong>completed</strong>?</p>
                    <div class="alert alert-info">
                        <strong>Payment Details:</strong><br>
                        <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
                        <strong>Student:</strong> {{ $payment->booking->student->name ?? 'N/A' }}<br>
                        <strong>Room:</strong> {{ $payment->booking->room->room_number ?? 'N/A' }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('payments.complete', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Mark as Completed
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fail Payment Modal -->
    <div class="modal fade" id="failModal{{ $payment->id }}" tabindex="-1" aria-labelledby="failModalLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="failModalLabel{{ $payment->id }}">
                        <i class="fas fa-times text-danger"></i> Mark Payment as Failed
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark this payment as <strong>failed</strong>?</p>
                    <div class="alert alert-warning">
                        <strong>Payment Details:</strong><br>
                        <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
                        <strong>Student:</strong> {{ $payment->booking->student->name ?? 'N/A' }}<br>
                        <strong>Room:</strong> {{ $payment->booking->room->room_number ?? 'N/A' }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('payments.fail', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Mark as Failed
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Refund Payment Modal -->
    @if($payment->status == 'completed')
    <div class="modal fade" id="refundModal{{ $payment->id }}" tabindex="-1" aria-labelledby="refundModalLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="refundModalLabel{{ $payment->id }}">
                        <i class="fas fa-undo text-info"></i> Refund Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark this payment as <strong>refunded</strong>?</p>
                    <div class="alert alert-info">
                        <strong>Payment Details:</strong><br>
                        <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
                        <strong>Student:</strong> {{ $payment->booking->student->name ?? 'N/A' }}<br>
                        <strong>Room:</strong> {{ $payment->booking->room->room_number ?? 'N/A' }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('payments.refund', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-undo"></i> Mark as Refunded
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Payment Modal -->
    <div class="modal fade" id="deleteModal{{ $payment->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $payment->id }}">
                        <i class="fas fa-trash text-danger"></i> Move to Trash
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to move this payment to trash?</p>
                    <div class="alert alert-warning">
                        <strong>Payment Details:</strong><br>
                        <strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}<br>
                        <strong>Student:</strong> {{ $payment->booking->student->name ?? 'N/A' }}<br>
                        <strong>Room:</strong> {{ $payment->booking->room->room_number ?? 'N/A' }}<br>
                        <strong>Status:</strong> <span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span>
                    </div>
                    <p class="text-muted"><small>This action will move the payment to trash. You can restore it later if needed.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Move to Trash
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
