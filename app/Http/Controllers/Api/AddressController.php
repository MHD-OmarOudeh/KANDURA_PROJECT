<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Services\AddressService;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $filters = $request->only(['search', 'city', 'district', 'sort_by', 'sort_direction', 'per_page']);

            $addresses = $this->addressService->getUserAddresses($user, $filters);

            return $this->success(
                AddressResource::collection($addresses),
                'Addresses retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve addresses', $e->getMessage(), 500);
        }
    }
    public function store(StoreAddressRequest $request)
    {
        try {
            $user = $request->user();
            $address = $this->addressService->createAddress($user, $request->validated());

            return $this->success(
                new AddressResource($address),
                'Address created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->error('Failed to create address', $e->getMessage(), 500);
        }
    }
    public function show(Request $request, Address $address)
    {
        try {
            $this->authorize('view', $address);

            return $this->success(
                new AddressResource($address),
                'Address retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('Unauthorized access');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve address', $e->getMessage(), 500);
        }
    }
    public function update(UpdateAddressRequest $request, Address $address)
    {
        try {
            $updatedAddress = $this->addressService->updateAddress($address, $request->validated());

            return $this->success(
                new AddressResource($updatedAddress),
                'Address updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to update address', $e->getMessage(), 500);
        }
    }
    public function destroy(Request $request, Address $address)
    {
        try {
            $this->authorize('delete', $address);

            $this->addressService->deleteAddress($address);

            return $this->success(null, 'Address deleted successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->forbidden('Unauthorized access');
        } catch (\Exception $e) {
            return $this->error('Failed to delete address', $e->getMessage(), 500);
        }
    }
}
