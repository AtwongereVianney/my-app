@extends('layouts.app')

@section('title', 'Rooms - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-bed"></i> Rooms</h1>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Room
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($rooms->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Current Occupancy</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>
                                <strong>{{ $room->room_number }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($room->type) }}</span>
                            </td>
                            <td>{{ $room->capacity }} person(s)</td>
                            <td>
                                @if($room->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Occupied</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $room->active_bookings_count }}/{{ $room->capacity }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this room?')">
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
            
            <div class="d-flex justify-content-center">
                {{ $rooms->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No rooms found</h4>
                <p class="text-muted">Start by adding your first room..</p>
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Room
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
