@extends('layouts.app')

@section('title', 'Payment Statistics - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0"><i class="fas fa-chart-bar"></i> Payment Statistics</h1>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-primary"><i class="fas fa-credit-card"></i> View Payments</a>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Total Payments</div>
          <div class="d-flex align-items-baseline">
            <div class="display-6 me-2">{{ number_format($totalPayments) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Completed</div>
          <div class="display-6 text-success">{{ number_format($completedPayments) }}</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Pending</div>
          <div class="display-6 text-warning">{{ number_format($pendingPayments) }}</div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Failed</div>
          <div class="display-6 text-danger">{{ number_format($failedPayments) }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Total Completed Amount</div>
          <div class="h3 text-dark">${{ number_format($totalAmount, 2) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Pending Amount</div>
          <div class="h3 text-warning">${{ number_format($pendingAmount, 2) }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-light">
      <strong><i class="fas fa-clock"></i> Recent Payments</strong>
    </div>
    <div class="card-body p-0">
      @if($recentPayments->count())
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Student</th>
              <th>Room</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Method</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentPayments as $payment)
              <tr>
                <td>#{{ $payment->id }}</td>
                <td>{{ $payment->booking->student->name ?? 'N/A' }}</td>
                <td>{{ $payment->booking->room->room_number ?? 'N/A' }}</td>
                <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
                <td>{{ $payment->payment_date?->format('M d, Y') }}</td>
                <td><span class="badge bg-secondary">{{ $payment->payment_method_label }}</span></td>
                <td><span class="badge {{ $payment->status_badge_class }}">{{ $payment->status_label }}</span></td>
                <td class="text-end">
                  <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-eye"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <div class="p-4 text-center text-muted">No recent payments to display.</div>
      @endif
    </div>
  </div>
@endsection


