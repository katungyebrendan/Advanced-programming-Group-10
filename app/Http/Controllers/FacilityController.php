<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Domain\Entities\FacilityEntity;
use App\Domain\Services\FacilityDomainService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use InvalidArgumentException;

class FacilityController extends Controller
{
    private FacilityDomainService $facilityService;

    public function __construct(FacilityDomainService $facilityService)
    {
        $this->facilityService = $facilityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return a view with a list of all facilities
        $facilities = $this->facilityService->getAll();
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the view with the facility creation form
        return view('facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFacilityRequest $request)
    {
        $data = $request->validated();
        // Normalize capabilities: accept comma-separated string from form and convert to array
        if (isset($data['capabilities']) && is_string($data['capabilities'])) {
            $capabilities = array_filter(array_map('trim', explode(',', $data['capabilities'])));
            $data['capabilities'] = array_values($capabilities);
        }
        $entity = new FacilityEntity(
            id: null,
            name: $data['name'] ?? '',
            location: $data['location'] ?? '',
            facilityType: $data['facility_type'] ?? '',
            description: $data['description'] ?? null,
            partnerOrganization: $data['partner_organization'] ?? null,
            capabilities: $data['capabilities'] ?? []
        );

        $result = $this->facilityService->createFacility($entity);

        if ($result['success']) {
            return redirect()->route('facilities.index')
                             ->with('success', 'Facility created successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to create facility'])]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $facility = $this->facilityService->findById($id);
        if (!$facility) {
            return redirect()->route('facilities.index')->withErrors(['message' => 'Facility not found']);
        }
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $facility = $this->facilityService->findById($id);
        if (!$facility) {
            return redirect()->route('facilities.index')->withErrors(['message' => 'Facility not found']);
        }
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacilityRequest $request, int $id)
    {
        $data = $request->validated();
        // Normalize capabilities: accept comma-separated string from form and convert to array
        if (isset($data['capabilities']) && is_string($data['capabilities'])) {
            $capabilities = array_filter(array_map('trim', explode(',', $data['capabilities'])));
            $data['capabilities'] = array_values($capabilities);
        }
        $entity = new FacilityEntity(
            id: $id,
            name: $data['name'] ?? '',
            location: $data['location'] ?? '',
            facilityType: $data['facility_type'] ?? '',
            description: $data['description'] ?? null,
            partnerOrganization: $data['partner_organization'] ?? null,
            capabilities: $data['capabilities'] ?? []
        );

        $result = $this->facilityService->updateFacility($entity);

        if ($result['success']) {
            return redirect()->route('facilities.index')
                             ->with('success', 'Facility updated successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to update facility'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $result = $this->facilityService->deleteFacility($id);

        if ($result['success']) {
            return redirect()->route('facilities.index')
                             ->with('success', 'Facility deleted successfully.');
        }

        return back()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to delete facility'])]);
    }
}