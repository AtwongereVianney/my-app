@extends('layouts.app')

@section('title', 'Bookings - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-check"></i> Bookings</h1>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Booking
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="card">
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Room</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                                </td>
                            <td>
                                <strong>{{ $booking->student->name ?? 'N/A' }}</strong>
                                @if($booking->student)
                                    <br><small class="text-muted">{{ $booking->student->email ?? '' }}</small>
                                @endif
                                </td>
                            <td>
                                <span class="badge bg-info">{{ $booking->room->room_number ?? 'N/A' }}</span>
                                @if($booking->room)
                                    <br><small class="text-muted">{{ ucfirst($booking->room->type) }}</small>
                                @endif
                                </td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}</td>
                            <td>
                                @if($booking->end_date)
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Ongoing</span>
                                @endif
                                </td>
                            <td>
                                @if($booking->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge bg-info">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                                </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($booking->status == 'active')
                                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" data-bs-target="#completeModal{{ $booking->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $booking->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($bookings->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No bookings found</h4>
                <p class="text-muted">Start by creating your first booking..</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Booking
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Action Modals -->
@foreach($bookings as $booking)
    <!-- Complete Booking Modal -->
    @if($booking->status == 'active')
    <div class="modal fade" id="completeModal{{ $booking->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $booking->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeModalLabel{{ $booking->id }}">
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
    <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $booking->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel{{ $booking->id }}">
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
    @endif

    <!-- Delete Booking Modal -->
    <div class="modal fade" id="deleteModal{{ $booking->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $booking->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $booking->id }}">
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
                        @endif<br>
                        <strong>Status:</strong> 
                        @if($booking->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($booking->status == 'completed')
                            <span class="badge bg-info">Completed</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
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
@endforeach
@endsection