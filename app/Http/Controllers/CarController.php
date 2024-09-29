<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Requests\Car\UploadFileRequest;
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
            $cars
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
        $car = $this->car->findById($id);
        return ApiResponse::success([
            'data' => $car
        ], 'Fetched', 'Car');
    }
    public function update(UpdateCarRequest $request, $id)
    {
        try {
            $car = $this->car->update($request->validated(), $id);
            return ApiResponse::success([
                'data' => $car
            ], 'Update', 'Car');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Update', 'Car');
        }
    }
    public function delete($id)
    {
        try {
            $deleteCar = $this->car->delete($id);
            return ApiResponse::success([
                'data' => $deleteCar
            ], 'Delete', 'Car');
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Delete', 'Car');
        }
    }
    public function uploadImage(UploadFileRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            return $this->car->uploadImage($request->validated(), $id);
        } catch (\Exception $exception) {
            return ApiResponse::error($exception->getMessage(), 'Upload', 'Image Car');
        }
    }

}
