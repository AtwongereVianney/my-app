<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function index()
    {
        $Booking = Booking::all();
        return view('Booking.index', compact('Booking'));
    }

    public function show($id)
    {
        $Booking = Booking::findOrFail($id);
        return view('Booking.show', compact('Booking'));
    }

    public function create()
    {
        return view('Booking.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Add validation rules for your model fields
            'field1' => 'required|string|max:255',
            'field2' => 'required|email|unique:table_name',
        ]);

        Booking::create($request->all());
        return redirect()->route('Booking.index')->with('success', 'Record created successfully!');
    }

    public function edit($id)
    {
        $Booking = Booking::findOrFail($id);
        return view('Booking.edit', compact('Booking'));
    }

    public function update(Request $request, $id)
    {
        $Booking = Booking::findOrFail($id);
        
        $request->validate([
            // Add validation rules (include unique rule exception for updates)
            'field2' => 'required|email|unique:table_name,field2,' . $Booking->id,
        ]);

        $Booking->update($request->all());
        return redirect()->route('Booking.index')->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        $Booking = Booking::findOrFail($id);
        $Booking->delete();
        return redirect()->route('Booking.index')->with('success', 'Record deleted successfully!');
    }
}
