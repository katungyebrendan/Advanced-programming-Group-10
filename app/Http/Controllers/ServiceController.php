<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
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
    public function store(CreateServiceRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            Service::create($validated);

            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the service: ' . $e->getMessage())->withInput();
        }
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
    public function update(UpdateServiceRequest $request, $id): RedirectResponse
    {
        try {
            $service = Service::findOrFail($id);
            $validated = $request->validated();
            $service->update($validated);

            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the service: ' . $e->getMessage())->withInput();
        }
    }

    // Delete a service
    public function destroy($id): RedirectResponse
    {
        try {
            $service = Service::findOrFail($id);
            
            if (!$service->canBeDeleted()) {
                return redirect()->route('services.index')
                                 ->with('error', $service->getDeletionBlockReason());
            }

            $service->delete();

            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('services.index')
                             ->with('error', 'An error occurred while deleting the service: ' . $e->getMessage());
        }
    }

    // Optional: list services by facility
    public function byFacility($facilityId): View
    {
        $services = Service::where('facility_id', $facilityId)->get();
        $facility = Facility::findOrFail($facilityId);
        
        return view('services.by-facility', compact('services', 'facility'));
    }
}