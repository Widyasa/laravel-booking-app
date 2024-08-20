<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required:cars,name|max:100',
            'license_plate' => "required|max:30|unique:cars,license_plate," . $this->id,
            'car_brand_id' => 'required|numeric',
            'car_supplier_id' => 'required|numeric',
            'price_per_day' => 'required|numeric',
            'stock' => 'required|numeric',
            'car_status' => 'required',
            'description' => 'required|min:3',
            'image' => 'required|file|image|mimes:png,jpg,jpeg,gif,webp,svg|max:5048',
        ];
    }
}
