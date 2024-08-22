<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function transaction_user()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function transaction_car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }
}
