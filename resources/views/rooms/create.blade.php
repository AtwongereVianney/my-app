@extends('layouts.app')

@section('title', 'Add New Room - Hostel Management')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus"></i> Add New Room</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('rooms.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number *</label>
                        <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                               id="room_number" name="room_number" value="{{ old('room_number') }}" required>
                        @error('room_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Room Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select room type</option>
                            <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                            <option value="suite" {{ old('type') == 'suite' ? 'selected' : '' }}>Suite</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity *</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                                   value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Room is available
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Room..
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
