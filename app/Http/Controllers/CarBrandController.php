<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarBrand\StoreCarBrandRequest;
use App\Http\Requests\CarBrand\UpdateCarBrandRequest;
use App\Models\CarBrand;
use App\Repositories\CarBrandRepository;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class CarBrandController extends Controller
{
    public function __construct(
        protected readonly CarBrandRepository $carBrand
    )
    {}

    public function index() {
        $brands = $this->carBrand->findAll();
        return ApiResponse::success([
            'brands' => $brands
        ], 'Fetched', 'Car Brand');
    }

    public function store(StoreCarBrandRequest $request)
    {
        try {
            $carBrand = $this->carBrand->store($request->validated());
            return ApiResponse::success([$carBrand], 'Create', 'Car Brand');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Create', 'Car Brand');
        }
    }

    public function show($id)
    {
        $brand = $this->carBrand->findById($id);
        return ApiResponse::success([
            'data' => $brand
        ], 'Fetched', 'Car Brand');
    }
    public function update(UpdateCarBrandRequest $request, $id)
    {
        try {
            $brand = $this->carBrand->update($request->validated(), $id);
            return ApiResponse::success([
                'data' => $brand
            ], 'Update', 'Car Brand');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Update', 'Car Brand');
        }
    }
    public function delete($id)
    {
        try {
            $brand = $this->carBrand->delete($id);
            return ApiResponse::success([
                'data' => $brand
            ], 'Delete', 'Car Brand');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Delete', 'Car Brand');
        }
    }

}
