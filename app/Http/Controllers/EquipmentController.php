<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Requests\CreateEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;

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
    public function store(CreateEquipmentRequest $request)
    {
        try {
            $validated = $request->validated();
            Equipment::create($validated);

            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the equipment: ' . $e->getMessage())->withInput();
        }
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
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        try {
            $validated = $request->validated();
            $equipment->update($validated);

            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the equipment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        try {
            if (!$equipment->canBeDeleted()) {
                return redirect()->route('equipment.index')
                               ->with('error', $equipment->getDeletionBlockReason());
            }

            $equipment->delete();

            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('equipment.index')
                           ->with('error', 'An error occurred while deleting the equipment: ' . $e->getMessage());
        }
    }
}
