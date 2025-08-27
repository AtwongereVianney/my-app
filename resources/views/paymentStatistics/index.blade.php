@extends('layouts.app')

@section('title', 'Payment Statistics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar text-primary"></i> Payment Statistics
            </h1>
            <p class="text-muted mb-0">Comprehensive payment analytics and insights</p>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-success" onclick="exportStats()">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('paymentStatistics.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="date_from" id="date_from" 
                                   value="{{ request('date_from', date('Y-m-01')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="date_to" id="date_to" 
                                   value="{{ request('date_to', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="payment_status" class="form-label">Status</label>
                            <select class="form-control" name="payment_status" id="payment_status">
                                <option value="">All Payments</option>
                                <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($totalRevenue ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($completedPayments ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Average Payment
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($averagePayment ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($pendingPayments ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trend Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trend</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="#" onclick="changeChartPeriod('daily')">Daily</a>
                            <a class="dropdown-item" href="#" onclick="changeChartPeriod('weekly')">Weekly</a>
                            <a class="dropdown-item" href="#" onclick="changeChartPeriod('monthly')">Monthly</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Completed</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Pending</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Failed</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Refunded</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods and Recent Transactions -->
    <div class="row mb-4">
        <!-- Payment Methods -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                </div>
                <div class="card-body">
                    @if(isset($paymentMethods) && count($paymentMethods) > 0)
                        @foreach($paymentMethods as $method)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        @if($method['method'] == 'credit_card')
                                            <i class="fas fa-credit-card fa-2x text-primary"></i>
                                        @elseif($method['method'] == 'bank_transfer')
                                            <i class="fas fa-university fa-2x text-success"></i>
                                        @elseif($method['method'] == 'mobile_money')
                                            <i class="fas fa-mobile-alt fa-2x text-warning"></i>
                                        @else
                                            <i class="fas fa-wallet fa-2x text-info"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $method['method'])) }}</div>
                                        <div class="text-muted small">{{ $method['count'] }} transactions</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold">${{ number_format($method['total'], 2) }}</div>
                                    <div class="text-muted small">{{ number_format($method['percentage'], 1) }}%</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No payment method data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Students by Payment -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Students by Payment Volume</h6>
                </div>
                <div class="card-body">
                    @if(isset($topStudents) && count($topStudents) > 0)
                        @foreach($topStudents as $index => $student)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <span class="badge badge-primary rounded-circle p-2">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $student['name'] }}</div>
                                        <div class="text-muted small">{{ $student['email'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold">${{ number_format($student['total_paid'], 2) }}</div>
                                    <div class="text-muted small">{{ $student['payment_count'] }} payments</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No student payment data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recentTransactions) && count($recentTransactions) > 0)
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction['id'] }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="rounded-circle" src="{{ $transaction['student_avatar'] ?? 'https://via.placeholder.com/40' }}" 
                                                         width="40" height="40" alt="Avatar">
                                                    <div class="ml-2">
                                                        <div class="font-weight-bold">{{ $transaction['student_name'] }}</div>
                                                        <div class="text-muted small">{{ $transaction['student_email'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="font-weight-bold">${{ number_format($transaction['amount'], 2) }}</td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $transaction['payment_method'])) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match($transaction['status']) {
                                                        'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        'refunded' => 'info',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge badge-{{ $statusClass }}">
                                                    {{ ucfirst($transaction['status']) }}
                                                </span>
                                            </td>
                                            <td>{{ date('M d, Y', strtotime($transaction['created_at'])) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewTransaction({{ $transaction['id'] }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($transaction['status'] == 'pending')
                                                    <button class="btn btn-sm btn-outline-success" onclick="approvePayment({{ $transaction['id'] }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No recent transactions found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js and custom scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($chartData ?? [1200, 1900, 3000, 2500, 2200, 3000]) !!},
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'Failed', 'Refunded'],
        datasets: [{
            data: {!! json_encode($statusData ?? [70, 20, 8, 2]) !!},
            backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// JavaScript functions
function refreshStats() {
    location.reload();
}

function exportStats() {
    // Implement export functionality
    alert('Export functionality would be implemented here');
}

function changeChartPeriod(period) {
    // Implement chart period change
    console.log('Changing chart period to:', period);
}

function viewTransaction(id) {
    // Implement view transaction
    alert('View transaction ' + id);
}

function approvePayment(id) {
    if(confirm('Are you sure you want to approve this payment?')) {
        // Implement payment approval
        alert('Payment ' + id + ' approved');
    }
}

// Initialize DataTable if available
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dataTable').DataTable({
            "pageLength": 10,
            "order": [[ 5, "desc" ]],
            "responsive": true
        });
    }
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-xs {
    font-size: 0.7rem;
}
.chart-area {
    position: relative;
    height: 320px;
    width: 100%;
}
.chart-pie {
    position: relative;
    height: 245px;
    width: 100%;
}
</style>
@endsection