<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeasurementResource;
use App\Models\Measurement;

class MeasurementController extends Controller
{
    public function index()
    {
        try {
            $measurements = Measurement::all();

            return $this->success(
                MeasurementResource::collection($measurements),
                'Measurements retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve measurements', $e->getMessage(), 500);
        }
    }

    public function show(Measurement $measurement)
    {
        try {
            return $this->success(
                new MeasurementResource($measurement),
                'Measurement retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve measurement', $e->getMessage(), 500);
        }
    }
}
