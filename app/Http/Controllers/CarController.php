<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Models\Car;
use App\Repositories\CarRepository;
use App\Utils\ApiResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(
        protected readonly CarRepository $car
    )
    {}

    public function index() {
        $cars = $this->car->findAll();
        return ApiResponse::success([
            'cars' => $cars
        ], 'Fetched', 'Car');
    }

    public function store(StoreCarRequest $request)
    {
        try {
            $car = $this->car->store($request->validated());
            return ApiResponse::success([$car], 'Create', 'Car');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Create', 'Car');
        }
    }

    public function show($id)
    {
        $brand = $this->car->findById($id);
        return ApiResponse::success([
            'data' => $brand
        ], 'Fetched', 'Car');
    }
    public function update(UpdateCarRequest $request, $id)
    {
        try {
            $brand = $this->car->update($request->validated(), $id);
            return ApiResponse::success([
                'data' => $brand
            ], 'Update', 'Car');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Update', 'Car');
        }
    }
    public function delete($id)
    {
        try {
            $brand = $this->car->delete($id);
            return ApiResponse::success([
                'data' => $brand
            ], 'Delete', 'Car');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Delete', 'Car');
        }
    }

}
