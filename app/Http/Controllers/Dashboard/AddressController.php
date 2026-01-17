<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Address;

class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'city_id', 'district', 'user_id', 'sort_by', 'sort_direction', 'per_page']);
        $addresses = $this->addressService->getAllAddresses($filters);

        // Get cities ordered by name in current locale
        $locale = app()->getLocale();
        $cities = \App\Models\City::orderByRaw("JSON_EXTRACT(name, '$.{$locale}')")->get();

        // Get unique districts from addresses
        $districts = Address::distinct()
                            ->whereNotNull('district')
                            ->pluck('district')
                            ->sort();

        return view('addresses.index', compact('addresses', 'cities', 'districts'));
    }

    public function show($id)
    {
        $address = Address::with(['user', 'city'])->findOrFail($id);
        return view('addresses.show', compact('address'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'district' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'building_number' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
        ]);

        $address = Address::findOrFail($id);
        $address->update($validated);

        return redirect()->route('dashboard.addresses.show', $id)
                        ->with('success', 'Address updated successfully!');
    }
    public function destroy(Address $address)
    {
        $this->addressService->deleteAddress($address);

        return redirect()
            ->route('dashboard.addresses.index')
            ->with('success', 'Address deleted successfully!');
    }
}
