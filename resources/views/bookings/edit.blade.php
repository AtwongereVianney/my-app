@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Booking #{{ $booking->id }}</h1>
        <div class="space-x-2">
            <a href="{{ route('booking.show', $booking->id) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>View
            </a>
            <a href="{{ route('booking.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-list mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-edit mr-2"></i>Update Booking Information
            </h3>
        </div>

        <form action="{{ route('booking.update', $booking->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
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
                            <option value="{{ $student->id }}" 
                                {{ (old('student_id', $booking->student_id) == $student->id) ? 'selected' : '' }}>
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