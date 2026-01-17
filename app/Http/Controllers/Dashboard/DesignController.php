<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Measurement;
use App\Models\DesignOption;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Display listing of all designs (Admin view)
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search',
            'measurement_id',
            'min_price',
            'max_price',
            'design_option_id',
            'user_id',
            'per_page'
        ]);

        $query = Design::with(['user:id,name,email', 'images', 'measurements', 'designOptions']);

        // Search (design name or user name)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                  ->orWhere('name->ar', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by size
        if (!empty($filters['measurement_id'])) {
            $query->filterByMeasurement($filters['measurement_id']);
        }

        // Filter by price range
        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $query->filterByPrice($filters['min_price'] ?? null, $filters['max_price'] ?? null);
        }

        // Filter by design option
        if (!empty($filters['design_option_id'])) {
            $query->filterByDesignOption($filters['design_option_id']);
        }

        // Filter by user
        if (!empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        $query->orderBy('created_at', 'desc');
        $designs = $query->paginate($filters['per_page'] ?? 15);

        // Data for filters
        $measurements = Measurement::all();
        $designOptions = DesignOption::all();

        return view('dashboard.designs.index', compact('designs', 'measurements', 'designOptions'));
    }

    /**
     * Show design details
     */
    public function show(Design $design)
    {
        $design->load(['user', 'images', 'measurements', 'designOptions']);

        return view('dashboard.designs.show', compact('design'));
    }
}
