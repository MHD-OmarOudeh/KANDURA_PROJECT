<?php

namespace App\Http\Services;

use App\Models\Design;
use App\Models\DesignImage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DesignService
{
    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $query->filterByPrice(
                $filters['min_price'] ?? null,
                $filters['max_price'] ?? null
            );
        }

        if (!empty($filters['measurement_id'])) {
            $query->filterByMeasurement($filters['measurement_id']);
        }

        if (!empty($filters['design_option_id'])) {
            $query->filterByDesignOption($filters['design_option_id']);
        }

        return $query;
    }

    public function getUserDesigns(User $user, array $filters = [])
    {
        $query = Design::query()
            ->forUser($user->id)
            ->withRelations();

        $this->applyFilters($query, $filters);

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getOtherDesigns(User $user, array $filters = [])
    {
        $query = Design::query()
            ->where('user_id', '!=', $user->id)
            ->withRelations();

        $this->applyFilters($query, $filters);

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function createDesign(User $user, array $data): Design
    {
        return DB::transaction(function () use ($user, $data) {
            $design = Design::create([
                'user_id'     => $user->id,
                'name'        => $data['name'],
                'description' => $data['description'],
                'price'       => $data['price'],
            ]);

            // Handle images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    $path = $image->store('designs', 'public');
                    $design->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0, // First image is primary
                    ]);
                }
            }

            // Attach measurements
            if (!empty($data['measurements'])) {
                $syncData = [];
                foreach ($data['measurements'] as $measurementId) {
                    $syncData[$measurementId] = ['quantity' => 0];
                }
                $design->measurements()->sync($syncData);
            }

            // Attach design options
            if (!empty($data['design_options'])) {
                $design->designOptions()->sync($data['design_options']);
            }

            return $design->load(['images', 'measurements', 'designOptions', 'user']);
        });
    }

    public function updateDesign(Design $design, array $data): Design
    {
        return DB::transaction(function () use ($design, $data) {
            $updateData = [];

            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }

            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }

            if (isset($data['price'])) {
                $updateData['price'] = $data['price'];
            }

            if (!empty($updateData)) {
                $design->update($updateData);
            }

            // Update images
            if (isset($data['images'])) {
                // Delete old images
                foreach ($design->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                }
                $design->images()->delete();

                // Add new images
                foreach ($data['images'] as $index => $image) {
                    $path = $image->store('designs', 'public');
                    $design->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // Update measurements
            if (isset($data['measurements'])) {
                $syncData = [];
                foreach ($data['measurements'] as $measurementId) {
                    $syncData[$measurementId] = ['quantity' => 0];
                }
                $design->measurements()->sync($syncData);
            }

            // Update design options
            if (isset($data['design_options'])) {
                $design->designOptions()->sync($data['design_options']);
            }

            return $design->fresh(['images', 'measurements', 'designOptions', 'user']);
        });
    }

    public function deleteDesign(Design $design): bool
    {
        return DB::transaction(function () use ($design) {
            // Delete all images from storage
            foreach ($design->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            return $design->delete();
        });
    }
}
