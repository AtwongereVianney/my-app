@extends('layouts.app')

@section('title', 'Trashed Bookings - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-trash"></i> Trashed Bookings</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('bookings.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Active Bookings..
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
        <h3 class="card-title mb-0">
            <i class="fas fa-trash-alt"></i> Deleted Bookings
        </h3>
    </div>
    
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
                            <th>Deleted At</th>
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
                                <small class="text-muted">{{ $booking->deleted_at->format('M d, Y g:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('bookings.restore', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                onclick="return confirm('Restore this booking?')">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('bookings.force-delete', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to permanently delete this booking? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i> Delete Forever
                                        </button>
                                    </form>
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
                <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No deleted bookings found</h4>
                <p class="text-muted">All deleted bookings will appear here.</p>
                <a href="{{ route('bookings.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Active Bookings
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
