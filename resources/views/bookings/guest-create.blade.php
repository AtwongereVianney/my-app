@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h1 class="text-2xl font-semibold mb-4">Book Room: {{ $room->name ?? ('#'.$room->id) }}</h1>

    <form method="POST" action="{{ route('guest.book.store', $room) }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium">Your Name</label>
            <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="w-full border rounded p-2" required>
            @error('guest_name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="w-full border rounded p-2" required>
            @error('guest_email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Phone (optional)</label>
            <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" class="w-full border rounded p-2">
            @error('guest_phone')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border rounded p-2" required>
                @error('start_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">End Date (optional)</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border rounded p-2">
                @error('end_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Book now</button>
    </form>
</div>
@endsection


