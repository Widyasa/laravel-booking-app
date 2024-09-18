<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarType\StoreCarTypeRequest;
use App\Http\Requests\CarType\UpdateCarTypeRequest;
use App\Repositories\CarTypeRepository;
use App\Utils\ApiResponse;
class CarTypeController extends Controller
{
    public function __construct(
        protected readonly CarTypeRepository $carType
    )
    {}

    public function index(): \Illuminate\Http\JsonResponse
    {
        $types = $this->carType->findAll();
        return ApiResponse::success([
            $types
        ], 'Fetched', 'Car Type');
    }

    public function store(StoreCarTypeRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $carType = $this->carType->store($request->validated());
            return ApiResponse::success([$carType], 'Create', 'Car Type');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Create', 'Car Type');
        }
    }

    public function show($id)
    {
        $type = $this->carType->findById($id);
        return ApiResponse::success([
            'data' => $type
        ], 'Fetched', 'Car Type');
    }
    public function update(UpdateCarTypeRequest $request, $id)
    {
        try {
            $type = $this->carType->update($request->validated(), $id);
            return ApiResponse::success([
                'data' => $type
            ], 'Update', 'Car Type');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Update', 'Car Type');
        }
    }
    public function delete($id)
    {
        try {
            $type = $this->carType->delete($id);
            return ApiResponse::success([
                'data' => $type
            ], 'Delete', 'Car Type');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Delete', 'Car Type');
        }
    }

}
