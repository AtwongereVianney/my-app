<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Room;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['student', 'room'])->latest()->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        $rooms = Room::where('is_available', true)->get();
        
        // If your views are in bookings folder, use this:
        return view('bookings.create', compact('students', 'rooms'));
        
        // If your views are in booking folder, use this:
        // return view('booking.create', compact('students', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled'
        ]);

        Booking::create($request->all());

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['student', 'room']);
        
        // If your views are in bookings folder, use this:
        return view('bookings.show', compact('booking'));
        
        // If your views are in booking folder, use this:
        // return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $students = Student::all();
        $rooms = Room::all();
        
        // If your views are in bookings folder, use this:
        return view('bookings.edit', compact('booking', 'students', 'rooms'));
        
        // If your views are in booking folder, use this:
        // return view('booking.edit', compact('booking', 'students', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $booking->update($request->all());

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete(); // This will now use soft delete

        return redirect()->route('bookings.index')
            ->with('success', 'Booking moved to trash successfully.');
    }

    /**
     * Mark booking as completed.
     */
    public function complete(Booking $booking)
    {
        $booking->update(['status' => 'completed', 'end_date' => now()]);

        return redirect()->back()
            ->with('success', 'Booking marked as completed.');
    }

    /**
     * Cancel booking.
     */
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Restore a soft-deleted booking.
     */
    public function restore($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $booking->restore();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking restored successfully.');
    }

    /**
     * Permanently delete a booking.
     */
    public function forceDelete($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $booking->forceDelete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking permanently deleted.');
    }

    /**
     * Show trashed bookings.
     */
    public function trashed()
    {
        $bookings = Booking::onlyTrashed()->with(['student', 'room'])->latest()->paginate(10);
        
        return view('bookings.trashed', compact('bookings'));
    }

    /**
     * Show guest booking form for a specific room (no auth required).
     */
    public function guestCreate(Room $room)
    {
        abort_unless($room->is_available, 404);

        return view('bookings.guest-create', [
            'room' => $room,
        ]);
    }

    /**
     * Store guest booking (no auth required).
     */
    public function guestStore(Request $request, Room $room)
    {
        abort_unless($room->is_available, 404);

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'nullable|string|max:50',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $guestToken = Str::random(40);

        $booking = Booking::create([
            'student_id' => null,
            'room_id' => $room->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'status' => 'active',
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'] ?? null,
            'guest_access_token' => $guestToken,
        ]);

        // Optionally mark room unavailable or keep availability logic elsewhere
        // $room->update(['is_available' => false]);

        return redirect()->route('guest.book.show', $guestToken)
            ->with('success', 'Booking created successfully. Keep this link to manage your booking.');
    }

    /**
     * Show a guest booking by token.
     */
    public function guestShow(string $token)
    {
        $booking = Booking::where('guest_access_token', $token)->with('room')->firstOrFail();

        return view('bookings.guest-show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Guest cancels booking by token.
     */
    public function guestCancel(Request $request, string $token)
    {
        $booking = Booking::where('guest_access_token', $token)->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('guest.book.show', $token)
            ->with('success', 'Booking cancelled successfully.');
    }
}