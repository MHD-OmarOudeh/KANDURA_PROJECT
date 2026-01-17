<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
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

    /**
     * Display listing of design options
     */
    public function index(Request $request)
    {
        $filters = $request->only(['type', 'search', 'per_page']);

        $query = DesignOption::query();

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                  ->orWhere('name->ar', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        $query->orderBy('created_at', 'desc');
        $options = $query->paginate($filters['per_page'] ?? 15);

        // Types for filter dropdown
        $types = [
            'color' => 'Color',
            'dome_type' => 'Dome Type',
            'fabric_type' => 'Fabric Type',
            'sleeve_type' => 'Sleeve Type',
        ];

        return view('dashboard.design-options.index', compact('options', 'types'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $types = [
            'color' => 'Color',
            'dome_type' => 'Dome Type',
            'fabric_type' => 'Fabric Type',
            'sleeve_type' => 'Sleeve Type',
        ];

        return view('dashboard.design-options.create', compact('types'));
    }

    /**
     * Store new design option
     */
    public function store(StoreDesignOptionRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $this->designOptionService->createDesignOption($data);

            return redirect()
                ->route('dashboard.design-options.index')
                ->with('success', 'Design option created successfully!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to create: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(DesignOption $designOption)
    {
        $types = [
            'color' => 'Color',
            'dome_type' => 'Dome Type',
            'fabric_type' => 'Fabric Type',
            'sleeve_type' => 'Sleeve Type',
        ];

        return view('dashboard.design-options.edit', compact('designOption', 'types'));
    }

    /**
     * Update design option
     */
    public function update(UpdateDesignOptionRequest $request, DesignOption $designOption)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $this->designOptionService->updateDesignOption($designOption, $data);

            return redirect()
                ->route('dashboard.design-options.index')
                ->with('success', 'Design option updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to update: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete design option
     */
    public function destroy(DesignOption $designOption)
    {
        try {
            $this->designOptionService->deleteDesignOption($designOption);

            return redirect()
                ->route('dashboard.design-options.index')
                ->with('success', 'Design option deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete: ' . $e->getMessage()]);
        }
    }
}
