@extends('layouts.app')

@section('title', 'Dashboard - Hostel Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-gauge"></i> Dashboard</h1>
            </div>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Total Rooms</h4>
                                    <h2 class="mb-0">{{ \App\Models\Room::count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Total Students</h4>
                                    <h2 class="mb-0">{{ \App\Models\Student::count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Active Bookings</h4>
                                    <h2 class="mb-0">{{ \App\Models\Booking::where('status', 'active')->count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Total Payments</h4>
                                    <h2 class="mb-0">{{ \App\Models\Payment::count() }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar"></i> Recent Bookings
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recentBookings = \App\Models\Booking::with(['student', 'room'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @if($recentBookings->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentBookings as $booking)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $booking->student->name }}</strong>
                                                <br>
                                                <small class="text-muted">Room {{ $booking->room->room_number }}</small>
                                            </div>
                                            <span class="badge bg-{{ $booking->status === 'active' ? 'success' : 'secondary' }} rounded-pill">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center">No recent bookings</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-line"></i> Recent Payments
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $recentPayments = \App\Models\Payment::with(['booking.student'])
                                    ->latest()
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @if($recentPayments->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentPayments as $payment)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $payment->booking->student->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $payment->amount }} - {{ $payment->payment_method }}</small>
                                            </div>
                                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }} rounded-pill">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center">No recent payments</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
