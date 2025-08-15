<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Student;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['student', 'room'])->get();
        return view('booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['student', 'room'])->findOrFail($id);
        return view('booking.show', compact('booking'));
    }

    public function create()
    {
        $students = Student::all();
        $rooms = Room::where('is_available', true)->get(); // Assuming rooms have availability status
        return view('booking.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,completed,cancelled'
        ]);

        // Check if room is available for the selected dates
        $conflictingBooking = Booking::where('room_id', $request->room_id)
            ->where('status', 'active')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date ?? $request->start_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date ?? $request->start_date])
                      ->orWhere(function ($subQuery) use ($request) {
                          $subQuery->where('start_date', '<=', $request->start_date)
                                   ->where(function ($endQuery) use ($request) {
                                       $endQuery->where('end_date', '>=', $request->end_date ?? $request->start_date)
                                                ->orWhereNull('end_date');
                                   });
                      });
            })
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['room_id' => 'This room is not available for the selected dates.']);
        }

        Booking::create([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status ?? 'active'
        ]);

        return redirect()->route('booking.index')->with('success', 'Booking created successfully!');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $students = Student::all();
        $rooms = Room::all();
        return view('booking.edit', compact('booking', 'students', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,completed,cancelled'
        ]);

        // Check for conflicts only if room or dates are changing
        if ($booking->room_id != $request->room_id || 
            $booking->start_date != $request->start_date || 
            $booking->end_date != $request->end_date) {
            
            $conflictingBooking = Booking::where('room_id', $request->room_id)
                ->where('id', '!=', $booking->id)
                ->where('status', 'active')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date ?? $request->start_date])
                          ->orWhereBetween('end_date', [$request->start_date, $request->end_date ?? $request->start_date])
                          ->orWhere(function ($subQuery) use ($request) {
                              $subQuery->where('start_date', '<=', $request->start_date)
                                       ->where(function ($endQuery) use ($request) {
                                           $endQuery->where('end_date', '>=', $request->end_date ?? $request->start_date)
                                                    ->orWhereNull('end_date');
                                       });
                          });
                })
                ->exists();

            if ($conflictingBooking) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['room_id' => 'This room is not available for the selected dates.']);
            }
        }

        $booking->update([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ]);

        return redirect()->route('booking.index')->with('success', 'Booking updated successfully!');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully!');
    }

    // Additional helper methods
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        return redirect()->route('booking.index')->with('success', 'Booking cancelled successfully!');
    }

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'completed']);
        return redirect()->route('booking.index')->with('success', 'Booking completed successfully!');
    }
}