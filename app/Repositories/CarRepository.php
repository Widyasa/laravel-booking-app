<?php
namespace App\Repositories;

use App\Models\Car;
use App\Utils\ApiResponse;
use App\Utils\UploadFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CarRepository {
    public function __construct(
        protected readonly Car $car,
        protected readonly UploadFile $uploadFile
    ) {}

    public function findAll()
    {
        $search = \request('search');
        return $this->car->latest()->with(['car_type', 'car_brand'])
            ->where('name', 'like', '%' . $search . '%')
            ->orwhere('license_plate', 'like', '%' . $search . '%')
            ->orwhere('description', 'like', '%' . $search . '%')
            ->orwhere('car_status', 'like', '%' . $search . '%')
            ->paginate(10)
            ->withQueryString();
    }

    public function findById(int $car_id): Car
    {
        return $this->car->where('id', $car_id)->with(['car_type', 'car_brand'])->first();
    }

    public function store($request): Car
    {
        DB::beginTransaction();
        try {
            $car = $this->car->create($request);
            DB::commit();
            return $car;
        } catch (\Exception $e) {
            logger($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }

    public function update($request, $id): bool
    {
        DB::beginTransaction();
        try {
            DB::commit();
            $car = $this->findById($id);
            return $car->update($request);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw $e;
        }
    }

    public function delete($id): bool
    {
        DB::beginTransaction();
        try {
            $car = $this->findById($id);
            $this->uploadFile->deleteExistFile("cars/thumbnails/$car->image");
            DB::commit();
            return $car->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            return $e->getMessage();
        }
    }

    public function uploadImage($request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $car = Car::findOrFail($id);
            if (isset($request["image"])) {
                $this->uploadFile->deleteExistFile($car->image);

                $filename = $this->uploadFile->uploadSingleFile($request['image'], 'cars/image/');
                $request['image'] = $filename;
            }
            $car->update($request);
            DB::commit();

            return ApiResponse::success([$car], 'Update', 'Car');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            throw $e;
        }
    }
}
