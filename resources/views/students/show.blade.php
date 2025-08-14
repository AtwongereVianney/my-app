@extends('layouts.app')

@section('title', 'Student Details - Hostel Management System')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-user"></i> Student Details
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Student ID</h6>
                        <p class="h5">{{ $student->id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Registration Date</h6>
                        <p class="h6">{{ $student->created_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h6 class="text-muted">Full Name</h6>
                        <p class="h5">
                            <i class="fas fa-user-circle text-primary"></i> {{ $student->name }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Email Address</h6>
                        <p>
                            <i class="fas fa-envelope text-info"></i>
                            <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Phone Number</h6>
                        <p>
                            <i class="fas fa-phone text-success"></i>
                            <a href="tel:{{ $student->phone }}">{{ $student->phone }}</a>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h6 class="text-muted">Address</h6>
                        <p class="bg-light p-3 rounded">
                            <i class="fas fa-map-marker-alt text-warning"></i> {{ $student->address }}
                        </p>
                    </div>
                </div>

                @if($student->bookings && $student->bookings->count() > 0)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Booking History</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-calendar-check"></i>
                            This student has <strong>{{ $student->bookings->count() }}</strong> booking(s).
                            <a href="{{ route('bookings.index') }}" class="alert-link">View All Bookings</a>
                        </div>
                    </div>
                </div>
                @else
                <hr>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    This student has no bookings yet.
                </div>
                @endif
            </div>
            
            <div class="card-footer">
                <div class="btn-group">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Students
                    </a>
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Student
                    </a>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this student?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection