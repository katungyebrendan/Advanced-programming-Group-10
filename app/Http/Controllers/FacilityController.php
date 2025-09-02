<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InvalidArgumentException;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return a view with a list of all facilities
        $facilities = Facility::all();
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
    public function store(Request $request)
    {
        // Validate the incoming data from the form
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'partner_organization' => 'nullable|string',
            'facility_type' => 'required|string',
            'capabilities' => 'nullable|string',
        ]);

        try {
            // Create a new facility record using the validated data
            Facility::create($request->all());

            // Redirect to the facilities list page with a success message
            return redirect()->route('facilities.index')
                             ->with('success', 'Facility created successfully.');
        } catch (InvalidArgumentException $e) {
            // Redirect back with an error message
            return back()->withInput()->withErrors(['message' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => 'Failed to create facility']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $facility = Facility::findOrFail($id);
        return view('facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $facility = Facility::findOrFail($id);
        return view('facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'partner_organization' => 'nullable|string',
            'facility_type' => 'required|string',
            'capabilities' => 'nullable|string',
        ]);

        try {
            $facility = Facility::findOrFail($id);
            $facility->update($request->all());

            return redirect()->route('facilities.index')
                             ->with('success', 'Facility updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => 'Failed to update facility']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $facility = Facility::findOrFail($id);
            if (!$facility->canBeDeleted()) {
                return back()->withErrors(['message' => $facility->getDeletionBlockReason()]);
            }

            $facility->delete();
            return redirect()->route('facilities.index')
                             ->with('success', 'Facility deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Failed to delete facility']);
        }
    }
}