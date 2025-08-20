@extends('layouts.app')

@section('title', 'Dashboard - Hostel Management')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h1 class="mb-0"><i class="fas fa-gauge"></i> Dashboard</h1>
</div>

<div class="row g-3">
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('rooms.index') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary"><i class="fas fa-bed fa-2x"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Rooms</div>
                        <div class="text-muted small">Manage rooms</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('students.index') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-success"><i class="fas fa-users fa-2x"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Students</div>
                        <div class="text-muted small">View and edit students</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('bookings.index') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-info"><i class="fas fa-calendar-check fa-2x"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Bookings</div>
                        <div class="text-muted small">Create and manage bookings</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('payments.index') }}" class="text-decoration-none">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-warning"><i class="fas fa-credit-card fa-2x"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Payments</div>
                        <div class="text-muted small">Record and track payments</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection


