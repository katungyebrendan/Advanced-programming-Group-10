<?php

namespace App\Http\Controllers;

use App\Models\Participants;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    public function index() {
        $participants = Participants::all();
        return view('participants.index', compact('participants'));
    }

    public function create() {
        return view('participants.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        Participants::create($data);
        return redirect()->route('participants.index')->with('success','Participants created.');
    }

    public function show(Participants $participant) {
        return view('participants.show', ['item' => $participant]);
    }

    public function edit(Participants $participant) {
        return view('participants.edit', ['item' => $participant]);
    }

    public function update(Request $request, Participants $participant) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $participant->update($data);
        return redirect()->route('participants.index')->with('success','Participants updated.');
    }

    public function destroy(Participants $participant) {
        $participant->delete();
        return redirect()->route('participants.index')->with('success','Participants deleted.');
    }
}
