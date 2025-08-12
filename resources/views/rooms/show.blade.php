@extends('layouts.app')

@section('title', 'Room Details - Hostel Management')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-bed"></i> Room: {{ $room->room_number }}</h4>
            <div>
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Room Information</h5>
                <table class="table">
                    <tr>
                        <td><strong>Room Number:</strong></td>
                        <td>{{ $room->room_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td><span class="badge bg-info">{{ ucfirst($room->type) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Capacity:</strong></td>
                        <td>{{ $room->capacity }} person(s)</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($room->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-danger">Occupied</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Current Occupancy:</strong></td>
                        <td>{{ $room->active_bookings_count }}/{{ $room->capacity }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
