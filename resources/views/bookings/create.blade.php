@extends('layouts.app')

@section('title', 'Create New Booking - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-plus"></i> Create New Booking</h1>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-plus"></i> Booking Information.
        </h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- Student Selection -->
                <div class="col-md-6 mb-3">
                    <label for="student_id" class="form-label">
                        Select Student <span class="text-danger">*</span>
                    </label>
                    <select name="student_id" id="student_id" 
                            class="form-select @error('student_id') is-invalid @enderror">
                        <option value="">Choose a student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} @if(isset($student->student_id)) ({{ $student->student_id }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Room Selection -->
                <div class="col-md-6 mb-3">
                    <label for="room_id" class="form-label">
                        Select Room <span class="text-danger">*</span>
                    </label>
                    <select name="room_id" id="room_id" 
                            class="form-select @error('room_id') is-invalid @enderror">
                        <option value="">Choose a room...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                Room {{ $room->room_number }} - {{ ucfirst($room->type) }} ({{ $room->capacity }} person(s))
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Start Date -->
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">
                        Start Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ old('start_date') }}" 
                           min="{{ date('Y-m-d') }}"
                           class="form-control @error('start_date') is-invalid @enderror">
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">
                        End Date <span class="text-muted">(Optional)</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ old('end_date') }}"
                           class="form-control @error('end_date') is-invalid @enderror">
                    <div class="form-text">Leave empty for ongoing booking</div>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create a Booking
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    // Update end date minimum when start date changes
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = '';
            }
        }
    });
    
    // Set initial minimum for end date
    if (startDateInput.value) {
        endDateInput.min = startDateInput.value;
    }
});
</script>
@endsection