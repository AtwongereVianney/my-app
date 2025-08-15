@extends('layouts.app')

@section('title', 'Create New Booking')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Booking</h1>
        <a href="{{ route('bookings.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-plus mr-2"></i>Booking Information
            </h3>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="p-6">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Student <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_id') border-red-500 @enderror">
                        <option value="">Choose a student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} @if(isset($student->student_id)) ({{ $student->student_id }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Selection -->
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Room <span class="text-red-500">*</span>
                    </label>
                    <select name="room_id" id="room_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('room_id') border-red-500 @enderror">
                        <option value="">Choose a room...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                Room {{ $room->room_number }}
                                @if(isset($room->building)) - {{ $room->building }} @endif
                                @if(isset($room->monthly_rent)) (â‚¦{{ number_format($room->monthly_rent) }}/month) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ old('start_date') }}" 
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        End Date <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ old('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for ongoing booking</p>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="mt-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="status" id="status" 
                        class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                <a href="{{ route('bookings.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Booking
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