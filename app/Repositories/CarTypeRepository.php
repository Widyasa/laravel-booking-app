<?php

namespace App\Repositories;

use App\Models\CarType;

class CarTypeRepository
{
    public function __construct(
        protected readonly CarType $carType
    ){}

    public function findAll()
    {
        $search = \request('search');
        return $this->carType
            ->where('name', 'like', '%' . $search . '%')
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
    public function findById(int $type_id): CarType {
        return $this->carType->where('id', $type_id)->first();
    }

    public function store($request):CarType
    {
        return $this->carType->create($request);
    }

    public function update($request, $id): bool
    {
        $carType = $this->findById($id);
        return $carType->update($request);
    }

    public function delete($id):bool
    {
        $carType = $this->findById($id);
        return $carType->delete();
    }
}
