<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $equipments = Equipment::paginate(10);
        return view('equipment.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = Facility::all();

        // Dropdown options
        $usageDomains = ['Research', 'Manufacturing', 'Testing', 'Education'];
        $supportPhases = ['Prototype', 'Production', 'Maintenance', 'R&D'];

        return view('equipment.create', compact('facilities', 'usageDomains', 'supportPhases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255',
            'capabilities' => 'nullable|string',
            'description' => 'nullable|string',
            'inventory_code' => 'nullable|string|max:100',
            'usage_domain' => 'nullable|string|max:100',
            'support_phase' => 'nullable|string|max:100',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipment.index')
                         ->with('success', 'Equipment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $facilities = Facility::all();
        $usageDomains = ['Research', 'Manufacturing', 'Testing', 'Education'];
        $supportPhases = ['Prototype', 'Production', 'Maintenance', 'R&D'];

        return view('equipment.edit', compact('equipment', 'facilities', 'usageDomains', 'supportPhases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255',
            'capabilities' => 'nullable|string',
            'description' => 'nullable|string',
            'inventory_code' => 'nullable|string|max:100',
            'usage_domain' => 'nullable|string|max:100',
            'support_phase' => 'nullable|string|max:100',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipment.index')
                         ->with('success', 'Equipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('equipment.index')
                         ->with('success', 'Equipment deleted successfully.');
    }
}
