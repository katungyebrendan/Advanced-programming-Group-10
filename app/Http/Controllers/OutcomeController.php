<?php

namespace App\Http\Controllers;

use App\Models\Outcomes;
use Illuminate\Http\Request;

class OutcomesController extends Controller
{
    public function index() {
        $outcomes = Outcomes::all();
        return view('outcomes.index', compact('outcomes'));
    }

    public function create() {
        return view('outcomes.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        Outcomes::create($data);
        return redirect()->route('outcomes.index')->with('success','Outcomes created.');
    }

    public function show(Outcomes $outcome) {
        return view('outcomes.show', ['item' => $outcome]);
    }

    public function edit(Outcomes $outcome) {
        return view('outcomes.edit', ['item' => $outcome]);
    }

    public function update(Request $request, Outcomes $outcome) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $outcome->update($data);
        return redirect()->route('outcomes.index')->with('success','Outcomes updated.');
    }

    public function destroy(Outcomes $outcome) {
        $outcome->delete();
        return redirect()->route('outcomes.index')->with('success','Outcomes deleted.');
    }
}
