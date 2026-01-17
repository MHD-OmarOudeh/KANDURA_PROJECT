<?php

namespace App\Http\Services;

use App\Models\Address;
use App\Models\User;

class AddressService
{
    /**
     * Create a new class instance.
     */
    protected $address;
    public function __construct(Address $address)
    {
        $this->address = $address;
    }
    public function createAddress(User $user, array $data): Address
    {
        return $user->addresses()->create([
            'city_id' => $data['city_id'],
            'district' => $data['district'],
            'street' => $data['street'],
            'building_number' => $data['building_number'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'additional_info' => $data['additional_info'] ?? null,
        ]);
    }
    public function updateAddress(Address $address, array $data): Address
    {
        $address->update([
            'city_id' => $data['city_id'] ?? $address->city_id,
            'district' => $data['district'] ?? $address->district,
            'street' => $data['street'] ?? $address->street,
            'building_number' => $data['building_number'] ?? $address->building_number,
            'latitude' => $data['latitude'] ?? $address->latitude,
            'longitude' => $data['longitude'] ?? $address->longitude,
            'additional_info' => $data['additional_info'] ?? $address->additional_info,
        ]);
        return $address->fresh();
    }
     public function deleteAddress(Address $address): bool
    {
        return $address->delete();
    }
    public function getUserAddresses(User $user, array $filters = [])
    {
        $query = Address::forUser($user->id);

        // Apply search
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply city filter
        if (!empty($filters['city'])) {
            $query->filterByCity($filters['city']);
        }

        // Apply district filter
        if (!empty($filters['district'])) {
            $query->filterByDistrict($filters['district']);
        }

        // Apply sorting
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->sortBy($sortField, $sortDirection);

        // Paginate
        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage);
    }
    public function getAllAddresses(array $filters = [])
    {
        $query = Address::with(['user:id,name,email', 'city']);

        // Apply search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->search($search)
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('city', function ($cityQuery) use ($search) {
                    // Search in all translations
                    $cityQuery->whereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"])
                            ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"]);
                });
            });
        }

        // Apply city filter (now using city_id)
        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        // Apply district filter
        if (!empty($filters['district'])) {
            $query->filterByDistrict($filters['district']);
        }

        // Apply user filter
        if (!empty($filters['user_id'])) {
            $query->forUser($filters['user_id']);
        }

        // Apply sorting
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        if ($sortField === 'city_id' || $sortField === 'city') {
            // Sort by city name in current locale
            $locale = app()->getLocale();
            $query->join('cities', 'addresses.city_id', '=', 'cities.id')
                ->select('addresses.*')
                ->orderByRaw("JSON_EXTRACT(cities.name, '$.{$locale}') {$sortDirection}");
        } else {
            $query->sortBy($sortField, $sortDirection);
        }

        // Paginate
        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage)->withQueryString();
    }

}
