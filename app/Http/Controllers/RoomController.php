<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Room;

class RoomController extends Controller
{
    public function index()
    {
        return view('rooms.index', [
            'rooms' => Room::all(),
            'current_page' => 'rooms'
        ]);
    }

    public function create() {
        return view('rooms.create');
    }

    public function processCreate()
    {
        $this->validate(request(), [
            'name' => 'string|required'
        ]);

        Room::create(request()->all());
        return back()->with([
            'message-success' => 'Ruangan ' . request()->name . ' berhasil ditambahkan!',
            'current_page' => 'rooms'
        ]);
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', [
            'room' => $room,
            'current_page' => 'rooms'
        ]);
    }

    public function delete(Room $room)
    {
        $room->delete();
        return back()->with('message-success', 'Data berhasil dihapus.');
    }

    public function processEdit(Room $room)
    {
        $this->validate(request(), [
            'name' => 'string|required',
            'grade' => 'integer|required'
        ]);

        $room->update( request()->all() );

        return redirect()->route('rooms.index')->with([
            'message-success' => 'Data Ruangan berhasil diubah.',
        ]);
    }
}
