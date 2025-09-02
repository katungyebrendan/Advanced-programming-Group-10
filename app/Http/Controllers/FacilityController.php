<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Http\Requests\CreateFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Facility::query();

            // Search by term
            if ($request->filled('search')) {
                $query->search($request->input('search'));
            }

            // Filter by type
            if ($request->filled('type')) {
                $query->byType($request->input('type'));
            }

            // Filter by partner
            if ($request->filled('partner')) {
                $query->byPartner($request->input('partner'));
            }

            $facilities = $query->get();
            return response()->json(['success' => true, 'data' => $facilities]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFacilityRequest $request)
    {
        try {
            // The validation is handled automatically by the CreateFacilityRequest class.
            $validatedData = $request->validated();
            $facility = Facility::create($validatedData);

            return response()->json(['success' => true, 'message' => 'Facility created successfully', 'data' => $facility], 201);
        } catch (\Exception $e) {
            // This will catch any other unexpected errors
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $facility = Facility::with(['services', 'equipment', 'projects'])->find($id);
            if (!$facility) {
                return response()->json(['success' => false, 'message' => 'Facility not found'], 404);
            }
            return response()->json(['success' => true, 'data' => $facility]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacilityRequest $request, $id)
    {
        try {
            $facility = Facility::find($id);
            if (!$facility) {
                return response()->json(['success' => false, 'message' => 'Facility not found'], 404);
            }

            // The validation is handled automatically by the UpdateFacilityRequest class.
            $validatedData = $request->validated();
            $facility->update($validatedData);

            return response()->json(['success' => true, 'message' => 'Facility updated successfully', 'data' => $facility]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $facility = Facility::find($id);
            if (!$facility) {
                return response()->json(['success' => false, 'message' => 'Facility not found'], 404);
            }

            if (!$facility->canBeDeleted()) {
                return response()->json(['success' => false, 'message' => $facility->getDeletionBlockReason()], 403);
            }

            $facility->delete();
            return response()->json(['success' => true, 'message' => 'Facility deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}