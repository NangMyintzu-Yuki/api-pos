<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTownshipRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "id" => "required",
            "name" => "required|unique:townships,name",
            "city_id" => "",
            "status" => "",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Name is Required",
            "city_id.required" => "City is Required",
        ];
    }
}
