<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTownshipRequest extends FormRequest
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
            "name" => "required|unique:townships,name",
            "city_id" => "required|not_in:null",
            "status" => "",
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Name is Required",
            "city_id.required" => "City is Required",
            "city_id.not_in" => "City is Required"
        ];
    }
}
