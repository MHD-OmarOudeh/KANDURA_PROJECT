<?php
namespace App\Http\Services;

use App\Models\DesignOption;
use Illuminate\Support\Facades\Storage;

class DesignOptionService
{
    public function createDesignOption(array $data): DesignOption
    {
        if (isset($data['image']) && is_object($data['image'])) {
            $data['image'] = $data['image']->store('design-options', 'public');
        }

        return DesignOption::create($data);
    }

    public function updateDesignOption(DesignOption $option, array $data): DesignOption
    {
        if (isset($data['image']) && is_object($data['image'])) {
            // Delete old image
            if ($option->image) {
                Storage::disk('public')->delete($option->image);
            }
            $data['image'] = $data['image']->store('design-options', 'public');
        }

        $option->update($data);
        return $option->fresh();
    }

    public function deleteDesignOption(DesignOption $option): bool
    {
        if ($option->image) {
            Storage::disk('public')->delete($option->image);
        }

        return $option->delete();
    }

    public function getAllOptions(array $filters = [])
    {
        $query = DesignOption::query();

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name->en', 'like', "%{$filters['search']}%")
                  ->orWhere('name->ar', 'like', "%{$filters['search']}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $filters['per_page'] ?? 50;
        return $query->paginate($perPage);
    }
}

