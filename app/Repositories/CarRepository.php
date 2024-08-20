<?php
namespace App\Repositories;

use App\Models\Car;
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
        return $this->car->latest()->paginate(10)->withQueryString();
    }

    public function findById(int $car_id): Car
    {
        return $this->car->where('id', $car_id)->with(['car_type', 'car_brand'])->first();
    }

    public function store($request): Car
    {
        DB::beginTransaction();
        try {
            if ($request["image"]) {
                $filename = $this->uploadFile->uploadSingleFile($request['image'], "cars/thumbnails/");
                $request['image'] = $filename;
            }

            $car = $this->car->create($request);

            DB::commit();

            return $car;

        } catch (\Exception $e) {
            logger($e->getMessage());
            DB::rollBack();

            throw $e;
        }
    }

    public function update(array $request, Car $car)
    {
        DB::beginTransaction();
        try {
            if (isset($request["image"])) {
                $this->uploadFile->deleteExistFile("cars/thumbnails/$car->image");

                $filename = $this->uploadFile->uploadSingleFile($request['image'], 'cars/thumbnails');
                $request['image'] = $filename;
            }

            DB::commit();

            return $car->update($request);

        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw $e;
        }
    }

    public function delete($car): bool
    {
        DB::beginTransaction();
        try {

            $this->uploadFile->deleteExistFile("cars/thumbnails/$car->image");

            DB::commit();

            return $car->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());

            throw $e->getMessage();
        }
    }
}
