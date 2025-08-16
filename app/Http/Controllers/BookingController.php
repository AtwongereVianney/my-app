<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Student;
use App\Models\Room;

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
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
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
}