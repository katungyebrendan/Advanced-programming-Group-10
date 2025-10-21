<?php

namespace App\Http\Controllers;

use App\Domain\Entities\EquipmentEntity;
use App\Domain\Services\EquipmentDomainService;
use App\Domain\Services\FacilityDomainService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;

class EquipmentController extends Controller
{
    private EquipmentDomainService $equipmentService;
    private FacilityDomainService $facilityService;

    public function __construct(
        EquipmentDomainService $equipmentService,
        FacilityDomainService $facilityService
    ) {
        $this->equipmentService = $equipmentService;
        $this->facilityService = $facilityService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipments = $this->equipmentService->getAll();
        return view('equipment.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facilities = $this->facilityService->getAll();

        // Dropdown options aligned with validation rules
        // CreateEquipmentRequest/UpdateEquipmentRequest allow: Electronics, Mechanical, Software, General
        $usageDomains = ['Electronics', 'Mechanical', 'Software', 'General'];
        // Coherence rule recognizes: Prototyping, Testing (and may reject Training-only)
        $supportPhases = ['Prototyping', 'Testing', 'Training'];

        return view('equipment.create', compact('facilities', 'usageDomains', 'supportPhases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEquipmentRequest $request)
    {
        $data = $request->validated();

        $entity = new EquipmentEntity(
            id: null,
            facilityId: $data['facility_id'] ?? 0,
            name: $data['name'] ?? '',
            inventoryCode: $data['inventory_code'] ?? '',
            description: $data['description'] ?? null,
            capabilities: $data['capabilities'] ?? null,
            usageDomain: $data['usage_domain'] ?? null,
            supportPhase: $data['support_phase'] ?? null
        );

        $result = $this->equipmentService->createEquipment($entity);

        if ($result['success']) {
            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment created successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to create equipment'])]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $equipment = $this->equipmentService->findById($id);
        if (!$equipment) {
            return redirect()->route('equipment.index')->withErrors(['message' => 'Equipment not found']);
        }
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $equipment = $this->equipmentService->findById($id);
        if (!$equipment) {
            return redirect()->route('equipment.index')->withErrors(['message' => 'Equipment not found']);
        }

        $facilities = $this->facilityService->getAll();
        $usageDomains = ['Electronics', 'Mechanical', 'Software', 'General'];
        $supportPhases = ['Prototyping', 'Testing', 'Training'];

        return view('equipment.edit', compact('equipment', 'facilities', 'usageDomains', 'supportPhases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, int $id)
    {
        $data = $request->validated();

        $entity = new EquipmentEntity(
            id: $id,
            facilityId: $data['facility_id'] ?? 0,
            name: $data['name'] ?? '',
            inventoryCode: $data['inventory_code'] ?? '',
            description: $data['description'] ?? null,
            capabilities: $data['capabilities'] ?? null,
            usageDomain: $data['usage_domain'] ?? null,
            supportPhase: $data['support_phase'] ?? null
        );

        $result = $this->equipmentService->updateEquipment($entity);

        if ($result['success']) {
            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment updated successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to update equipment'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $result = $this->equipmentService->deleteEquipment($id);

        if ($result['success']) {
            return redirect()->route('equipment.index')
                             ->with('success', 'Equipment deleted successfully.');
        }

        return back()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to delete equipment'])]);
    }
}
