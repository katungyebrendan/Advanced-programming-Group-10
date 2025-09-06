<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServiceController extends Controller
{
    // List all services
    public function index(): View
    {
        $services = Service::with('facility')->get();
        return view('services.index', compact('services'));
    }

    // Show form to create a new service
    public function create(): View
    {
        $facilities = Facility::all();
        $categories = Service::CATEGORIES;
        $skillTypes = Service::SKILL_TYPES;

        return view('services.create', compact('facilities', 'categories', 'skillTypes'));
    }

    // Store a new service
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', Service::CATEGORIES),
            'skill_type' => 'required|in:' . implode(',', Service::SKILL_TYPES),
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    // Show a single service
    public function show($id): View
    {
        $service = Service::with('facility')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Show form to edit a service
    public function edit($id): View
    {
        $service = Service::findOrFail($id);
        $facilities = Facility::all();
        $categories = Service::CATEGORIES;
        $skillTypes = Service::SKILL_TYPES;

        return view('services.edit', compact('service', 'facilities', 'categories', 'skillTypes'));
    }

    // Update a service
    public function update(Request $request, $id): RedirectResponse
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255|unique:services,name,' . $service->service_id . ',service_id',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', Service::CATEGORIES),
            'skill_type' => 'required|in:' . implode(',', Service::SKILL_TYPES),
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    // Delete a service
    public function destroy($id): RedirectResponse
    {
        $service = Service::findOrFail($id);
        
        if (!$service->canBeDeleted()) {
            return redirect()->route('services.index')
                             ->with('error', $service->getDeletionBlockReason());
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    // Optional: list services by facility
    public function byFacility($facilityId): View
    {
        $services = Service::where('facility_id', $facilityId)->get();
        $facility = Facility::findOrFail($facilityId);
        
        return view('services.by-facility', compact('services', 'facility'));
    }
}