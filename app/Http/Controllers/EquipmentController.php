<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    // Display paginated equipment list
    public function index() {
        $equipment = Equipment::paginate(10); 
        return view('equipment.index', compact('equipment'));
    }

    // Show form to create new equipment
    public function create() {
        $facilities = Facility::all();

        // Define usage domains and support phases
        $usageDomains = ['Research', 'Teaching', 'Maintenance', 'Production'];
        $supportPhases = ['Planning', 'Implementation', 'Monitoring', 'Evaluation'];

        return view('equipment.create', compact('facilities', 'usageDomains', 'supportPhases'));
    }

    // Store new equipment
    public function store(Request $request) {
        $data = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'capabilities' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'inventory_code' => 'nullable|string|max:100',
            'usage_domain' => 'nullable|string|max:100',
            'support_phase' => 'nullable|string|max:100',
        ]);

        Equipment::create($data);

        return redirect()->route('equipment.index')->with('success','Equipment created.');
    }

    // Show single equipment
    public function show(Equipment $equipment) {
        return view('equipment.show', ['item' => $equipment]);
    }

    // Show form to edit equipment
    public function edit(Equipment $equipment) {
        $facilities = Facility::all();
        $usageDomains = ['Research', 'Teaching', 'Maintenance', 'Production'];
        $supportPhases = ['Planning', 'Implementation', 'Monitoring', 'Evaluation'];

        return view('equipment.edit', compact('equipment','facilities','usageDomains','supportPhases'));
    }

    // Update equipment
    public function update(Request $request, Equipment $equipment) {
        $data = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'name' => 'required|string|max:255',
            'capabilities' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'inventory_code' => 'nullable|string|max:100',
            'usage_domain' => 'nullable|string|max:100',
            'support_phase' => 'nullable|string|max:100',
        ]);

        $equipment->update($data);

        return redirect()->route('equipment.index')->with('success','Equipment updated.');
    }

    // Delete equipment
    public function destroy(Equipment $equipment) {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success','Equipment deleted.');
    }
}
