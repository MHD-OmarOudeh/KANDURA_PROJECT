<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Http\Services\DesignService;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    protected DesignService $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    /**
     * Get user's own designs
     * GET /api/designs?filter=my
     */
    public function index(Request $request)
{
    try {
        $user = Auth::user();
        $filter = $request->input('filter', 'my'); // my | others

        if ($filter === 'others') {
            $designs = $this->designService->getOtherDesigns($user, $request->all());
        } else {
            $designs = $this->designService->getUserDesigns($user, $request->all());
        }

        return $this->success(
            DesignResource::collection($designs)->response()->getData(true),
            'Designs retrieved successfully'
        );

    } catch (\Exception $e) {
        return $this->error('Failed to retrieve designs', $e->getMessage(), 500);
    }
}


    /**
     * Create new design
     * POST /api/designs
     */
    public function store(StoreDesignRequest $request)
    {
        try {
            $user = Auth::user();
            $design = $this->designService->createDesign($user, $request->validated());

            return $this->success(
                new DesignResource($design),
                'Design created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create design', $e->getMessage(), 500);
        }
    }

    /**
     * Get specific design
     * GET /api/designs/{id}
     */
    public function show(Request $request, Design $design)
    {
        try {
            $this->authorize('view', $design);

            $design->load(['images', 'measurements', 'designOptions', 'user']);

            return $this->success(
                new DesignResource($design),
                'Design retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('You do not have permission to view this design');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve design', $e->getMessage(), 500);
        }
    }

    /**
     * Update design
     * PUT/PATCH /api/designs/{id}
     */
    public function update(UpdateDesignRequest $request, Design $design)
    {
        try {
            $this->authorize('update', $design);
            $updatedDesign = $this->designService->updateDesign($design, $request->validated());

            return $this->success(
                new DesignResource($updatedDesign),
                'Design updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update design', $e->getMessage(), 500);
        }
    }

    /**
     * Delete design
     * DELETE /api/designs/{id}
     */
    public function destroy(Request $request, Design $design)
    {
        try {
            $this->authorize('delete', $design);

            $this->designService->deleteDesign($design);

            return $this->success(null, 'Design deleted successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('You do not have permission to delete this design');
        } catch (\Exception $e) {
            return $this->error('Failed to delete design', $e->getMessage(), 500);
        }
    }
}
