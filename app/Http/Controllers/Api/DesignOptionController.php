<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Http\Resources\DesignOptionResource;
use App\Http\Services\DesignOptionService;
use App\Models\DesignOption;
use Illuminate\Http\Request;

class DesignOptionController extends Controller
{
    protected DesignOptionService $designOptionService;

    public function __construct(DesignOptionService $designOptionService)
    {
        $this->designOptionService = $designOptionService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['type', 'search', 'per_page']);
            $options = $this->designOptionService->getAllOptions($filters);

            return $this->success(
                DesignOptionResource::collection($options)->response()->getData(true),
                'Design options retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve design options', $e->getMessage(), 500);
        }
    }

    public function store(StoreDesignOptionRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $option = $this->designOptionService->createDesignOption($data);

            return $this->success(
                new DesignOptionResource($option),
                'Design option created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create design option', $e->getMessage(), 500);
        }
    }

    public function show(DesignOption $designOption)
    {
        try {
            return $this->success(
                new DesignOptionResource($designOption),
                'Design option retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve design option', $e->getMessage(), 500);
        }
    }

    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $updatedOption = $this->designOptionService->updateDesignOption($designOption, $data);

            return $this->success(
                new DesignOptionResource($updatedOption),
                'Design option updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update design option', $e->getMessage(), 500);
        }
    }

    public function destroy(DesignOption $designOption)
    {
        try {
            $this->designOptionService->deleteDesignOption($designOption);

            return $this->success(null, 'Design option deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete design option', $e->getMessage(), 500);
        }
    }
}
