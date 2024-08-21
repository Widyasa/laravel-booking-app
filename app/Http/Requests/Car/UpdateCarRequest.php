<?php

namespace App\Http\Requests\Car;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'license_plate' => "required|max:30",
            'car_brand_id' => 'required|numeric',
            'car_type_id' => 'required|numeric',
            'price_per_day' => 'required|numeric',
            'car_status' => 'required',
            'description' => 'required|min:3',
            'image' => 'required|file|image|mimes:png,jpg,jpeg,gif,webp,svg|max:5048',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
