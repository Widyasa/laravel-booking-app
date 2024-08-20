<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'license_plate',
        'image',
        'price_per_day',
        'description',
        'car_status',
        'car_brand_id',
        'car_type_id'
    ];

    public function carBrand() {
        return $this->belongsTo(CarBrand::class);
    }
    public function carType() {
        return $this->belongsTo(CarType::class);
    }
}
