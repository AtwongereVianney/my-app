@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Booking Details</h1>
        <div class="space-x-2">
            <a href="{{ route('bookings.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
            
            @if($booking->status == 'active')
                <a href="{{ route('bookings.edit', $booking->id) }}" 
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bed mr-2"></i>
                Booking #{{ $booking->id }}
                <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($booking->status == 'active') bg-green-100 text-green-800
                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($booking->status) }}
                </span>
            </h3>
        </div>

        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Student Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Student Information</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Name</label>
                            <p class="text-gray-900">{{ $booking->student->name ?? 'N/A' }}</p>
                        </div>
                        
                        @if(isset($booking->student->email))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-900">{{ $booking->student->email }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->student->student_id))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Student ID</label>
                            <p class="text-gray-900">{{ $booking->student->student_id }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->student->phone))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone</label>
                            <p class="text-gray-900">{{ $booking->student->phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Room Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2">Room Information</h4>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Room Number</label>
                            <p class="text-gray-900">{{ $booking->room->room_number ?? 'N/A' }}</p>
                        </div>
                        
                        @if(isset($booking->room->building))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Building</label>
                            <p class="text-gray-900">{{ $booking->room->building }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->room->floor))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Floor</label>
                            <p class="text-gray-900">{{ $booking->room->floor }}</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->room->capacity))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Capacity</label>
                            <p class="text-gray-900">{{ $booking->room->capacity }} person(s)</p>
                        </div>
                        @endif
                        
                        @if(isset($booking->room->monthly_rent))
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Monthly Rent</label>
                            <p class="text-gray-900">${{ number_format($booking->room->monthly_rent, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Booking Details</h4>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Start Date</label>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->start_date)->diffForHumans() }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">End Date</label>
                        @if($booking->end_date)
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->end_date)->format('F d, Y') }}</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->end_date)->diffForHumans() }}</p>
                        @else
                            <p class="text-gray-900">Ongoing</p>
                            <p class="text-sm text-gray-500">No end date specified</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Duration</label>
                        @if($booking->end_date)
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) + 1 }} days</p>
                        @else
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::now()) + 1 }} days (ongoing)</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid md:grid-cols-2 gap-6 text-sm text-gray-600">
                    <div>
                        <label class="block font-medium">Created</label>
                        <p>{{ $booking->created_at->format('F d, Y at g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block font-medium">Last Updated</label>
                        <p>{{ $booking->updated_at->format('F d, Y at g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($booking->status == 'active')
                <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-4">
                    <form action="{{ route('bookings.complete', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                                onclick="return confirm('Mark this booking as completed?')">
                            <i class="fas fa-check mr-2"></i>Mark as Completed
                        </button>
                    </form>
                    
                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                                onclick="return confirm('Cancel this booking?')">
                            <i class="fas fa-times mr-2"></i>Cancel Booking
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection