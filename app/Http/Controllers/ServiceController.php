<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Facility;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // List all services
    public function index()
    {
        $services = Service::with('facility')->get();
        return view('services.index', compact('services'));
    }

    // Show form to create a new service
    public function create()
    {
        $facilities = Facility::all(); // fetch facilities for dropdown
        return view('services.create', compact('facilities'));
    }

    // Store a new service
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'skill_type' => 'nullable|string|max:255',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    // Show a single service
    public function show($id)
    {
        $service = Service::with('facility')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Show form to edit a service
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $facilities = Facility::all();
        return view('services.edit', compact('service', 'facilities'));
    }

    // Update a service
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'skill_type' => 'nullable|string|max:255',
        ]);

        $service = Service::findOrFail($id);
        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    // Delete a service
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    // Optional: list services by facility
    public function byFacility($facilityId)
    {
        $services = Service::where('facility_id', $facilityId)->get();
        return view('services.index', compact('services', 'facilityId'));
    }
}
