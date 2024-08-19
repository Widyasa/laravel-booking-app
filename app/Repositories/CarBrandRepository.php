<?php

namespace App\Repositories;

use App\Models\Carbrand;

class CarBrandRepository
{
    public function __construct(
        protected readonly CarBrand $carBrand
    ){}

    public function findAll()
    {
        return $this->carBrand->latest()->get();
    }
    public function findById(int $type_id): Carbrand {
        return $this->carBrand->where('id', $type_id)->first();
    }

    public function store($request):Carbrand
    {
        return $this->carBrand->create($request);
    }

    public function update($request, $id): bool
    {
        $carBrand = $this->findById($id);
        return $carBrand->update($request);
    }

    public function delete($id):bool
    {
        $carBrand = $this->findById($id);
        return $carBrand->delete();
    }
}
