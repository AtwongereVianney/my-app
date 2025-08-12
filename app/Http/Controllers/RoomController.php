<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::withCount(['bookings', 'activeBookings'])->paginate(20);
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'capacity' => 'required|integer|min:1',
            'type' => 'required|in:single,double,suite',
            'is_available' => 'boolean',
        ]);

        Room::create($validated);
        return redirect()->route('rooms.index')->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::withCount(['bookings', 'activeBookings'])->findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $validated = $request->validate([
            'room_number' => 'sometimes|required|string|max:50|unique:rooms,room_number,' . $room->id,
            'capacity' => 'sometimes|required|integer|min:1',
            'type' => 'sometimes|required|in:single,double,suite',
            'is_available' => 'boolean',
        ]);

        $room->update($validated);
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully!');
    }
}
