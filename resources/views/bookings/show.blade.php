@extends('layouts.app')

@section('title', 'Booking Details - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-check"></i> Booking Details</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        
        @if($booking->status == 'active')
            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
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
            <i class="fas fa-bed me-2"></i>
            Booking #{{ $booking->id }}
            <span class="badge ms-3
                @if($booking->status == 'active') bg-success
                @elseif($booking->status == 'completed') bg-info
                @else bg-danger @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </h3>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Student Information -->
            <div class="col-md-6">
                <div class="mb-4">
                    <h4 class="h5 text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i>Student Information
                    </h4>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Name</label>
                            <p class="form-control-plaintext">{{ $booking->student->name ?? 'N/A' }}</p>
                        </div>
                        
                        @if(isset($booking->student->email))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Email</label>
                            <p class="form-control-plaintext">{{ $booking->student->email }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->student->student_id))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Student ID</label>
                            <p class="form-control-plaintext">{{ $booking->student->student_id }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->student->phone))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Phone</label>
                            <p class="form-control-plaintext">{{ $booking->student->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Room Information -->
            <div class="col-md-6">
                <div class="mb-4">
                    <h4 class="h5 text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-door-open me-2"></i>Room Information
                    </h4>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Room Number</label>
                            <p class="form-control-plaintext">{{ $booking->room->room_number ?? 'N/A' }}</p>
                        </div>
                        
                        @if(isset($booking->room->type))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Type</label>
                            <p class="form-control-plaintext">{{ ucfirst($booking->room->type) }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->room->capacity))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Capacity</label>
                            <p class="form-control-plaintext">{{ $booking->room->capacity }} person(s)</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->room->is_available))
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="form-control-plaintext">
                                @if($booking->room->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Occupied</span>
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="mt-4">
            <h4 class="h5 text-primary border-bottom pb-2 mb-3">
                <i class="fas fa-calendar-alt me-2"></i>Booking Details
            </h4>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted">Start Date</label>
                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($booking->start_date)->format('F d, Y') }}</p>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->start_date)->diffForHumans() }}</small>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted">End Date</label>
                    @if($booking->end_date)
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($booking->end_date)->format('F d, Y') }}</p>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->end_date)->diffForHumans() }}</small>
                    @else
                        <p class="form-control-plaintext">Ongoing</p>
                        <small class="text-muted">No end date specified</small>
                    @endif
                </div>
                
                <div class="col-md-4">
                    <label class="form-label fw-bold text-muted">Duration</label>
                    @if($booking->end_date)
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) + 1 }} days</p>
                    @else
                        <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::now()) + 1 }} days (ongoing)</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="mt-4 pt-3 border-top">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Created</label>
                    <p class="form-control-plaintext">{{ $booking->created_at->format('F d, Y at g:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold text-muted">Last Updated</label>
                    <p class="form-control-plaintext">{{ $booking->updated_at->format('F d, Y at g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($booking->status == 'active')
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="fas fa-check"></i> Mark as Completed
                    </button>
                    
                    <button type="button" class="btn btn-warning" 
                            data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times"></i> Cancel Booking
                    </button>
                    
                    <button type="button" class="btn btn-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Move to Trash
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Action Modals -->
@if($booking->status == 'active')
<!-- Complete Booking Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">
                    <i class="fas fa-check text-success"></i> Complete Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this booking as <strong>completed</strong>?</p>
                <div class="alert alert-info">
                    <strong>Booking Details:</strong><br>
                    <strong>Student:</strong> {{ $booking->student->name ?? 'N/A' }}<br>
                    <strong>Room:</strong> {{ $booking->room->room_number ?? 'N/A' }}<br>
                    <strong>Period:</strong> {{ $booking->start_date->format('M d, Y') }} - 
                    @if($booking->end_date)
                        {{ $booking->end_date->format('M d, Y') }}
                    @else
                        Ongoing
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('bookings.complete', $booking) }}" method="POST" class="d-inline">
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

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-times text-warning"></i> Cancel Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <strong>cancel</strong> this booking?</p>
                <div class="alert alert-warning">
                    <strong>Booking Details:</strong><br>
                    <strong>Student:</strong> {{ $booking->student->name ?? 'N/A' }}<br>
                    <strong>Room:</strong> {{ $booking->room->room_number ?? 'N/A' }}<br>
                    <strong>Period:</strong> {{ $booking->start_date->format('M d, Y') }} - 
                    @if($booking->end_date)
                        {{ $booking->end_date->format('M d, Y') }}
                    @else
                        Ongoing
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-times"></i> Cancel Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Booking Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash text-danger"></i> Move to Trash
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to move this booking to trash?</p>
                <div class="alert alert-warning">
                    <strong>Booking Details:</strong><br>
                    <strong>Student:</strong> {{ $booking->student->name ?? 'N/A' }}<br>
                    <strong>Room:</strong> {{ $booking->room->room_number ?? 'N/A' }}<br>
                    <strong>Period:</strong> {{ $booking->start_date->format('M d, Y') }} - 
                    @if($booking->end_date)
                        {{ $booking->end_date->format('M d, Y') }}
                    @else
                        Ongoing
                    @endif
                </div>
                <p class="text-muted"><small>This action will move the booking to trash. You can restore it later if needed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline">
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
@endif
    </div>
</div>
@endsection