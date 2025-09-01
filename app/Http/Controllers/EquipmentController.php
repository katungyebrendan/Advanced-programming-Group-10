<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index() {
        $equipment = Equipment::all();
        return view('equipment.index', compact('equipment'));
    }

    public function create() {
        return view('equipment.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        Equipment::create($data);
        return redirect()->route('equipment.index')->with('success','Equipment created.');
    }

    public function show(Equipment $equipment) {
        return view('equipment.show', ['item' => $equipment]);
    }

    public function edit(Equipment $equipment) {
        return view('equipment.edit', ['item' => $equipment]);
    }

    public function update(Request $request, Equipment $equipment) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $equipment->update($data);
        return redirect()->route('equipment.index')->with('success','Equipment updated.');
    }

    public function destroy(Equipment $equipment) {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success','Equipment deleted.');
    }
}
