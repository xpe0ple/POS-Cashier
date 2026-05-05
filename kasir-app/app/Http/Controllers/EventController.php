<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // 🔥 LIST
    public function index()
    {
        $events = Event::latest()->get();
        return view('events.index', compact('events'));
    }

    // 🔥 FORM CREATE
    public function create()
    {
        return view('events.create');
    }

    // 🔥 SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Event::create($request->all());

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil ditambahkan');
    }

    // 🔥 FORM EDIT
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    // 🔥 UPDATE
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil diupdate');
    }

    // 🔥 DELETE
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
