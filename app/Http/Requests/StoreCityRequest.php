<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
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
            "name"=>"required|unique:cities,name",
            "division_id"=>"required|not_in:null",
            "status"=>"",
        ];
    }
    public function messages()
    {
        return [
            "name.required"=>"Name is Required",
            "division_id.required"=>"Division is Required",
            "division_id.not_in"=>"Division is Required"
        ];
    }
}
