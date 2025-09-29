@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h1 class="text-2xl font-semibold mb-4">Your Booking</h1>

    <div class="bg-white shadow rounded p-4 mb-4">
        <p><strong>Room:</strong> {{ $booking->room->name ?? ('#'.$booking->room_id) }}</p>
        <p><strong>Name:</strong> {{ $booking->guest_name }}</p>
        <p><strong>Email:</strong> {{ $booking->guest_email }}</p>
        <p><strong>Phone:</strong> {{ $booking->guest_phone ?? '-' }}</p>
        <p><strong>Start:</strong> {{ optional($booking->start_date)->format('Y-m-d') }}</p>
        <p><strong>End:</strong> {{ optional($booking->end_date)->format('Y-m-d') ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
    </div>

    @if($booking->status !== 'cancelled')
    <form method="POST" action="{{ route('guest.book.cancel', $booking->guest_access_token) }}">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Cancel booking</button>
    </form>
    @endif
</div>
@endsection


