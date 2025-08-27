<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Services\FacilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class FacilityController extends Controller
{
    public function __construct(
        private FacilityService $facilityService
    ) {}

    /**
     * List all facilities
     * GET /api/facilities
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $facilities = $this->facilityService->getFacilities(
                $request->query('type'),
                $request->query('partner')
            );

            return response()->json([
                'success' => true,
                'data' => $facilities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facilities'
            ], 500);
        }
    }

    /**
     * Get specific facility
     * GET /api/facilities/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $facility = $this->facilityService->getFacilityById($id);

            if (!$facility) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve facility'
            ], 500);
        }
    }

    /**
     * Create new facility
     * POST /api/facilities
     */
    public function store(CreateFacilityRequest $request): JsonResponse
    {
        try {
            // The request is already validated by Laravel
            $facility = $this->facilityService->createFacility($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Facility created successfully',
                'data' => $facility
            ], 201);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create facility'
            ], 500);
        }
    }

    /**
     * Update facility
     * PUT /api/facilities/{id}
     */
    public function update(UpdateFacilityRequest $request, int $id): JsonResponse
    {
        try {
            $facility = $this->facilityService->updateFacility($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Facility updated successfully',
                'data' => $facility
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update facility'
            ], 500);
        }
    }

    /**
     * Delete facility
     * DELETE /api/facilities/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->facilityService->deleteFacility($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Facility deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete facility'
            ], 500);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete facility'
            ], 500);
        }
    }
}