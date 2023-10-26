<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
            "latitude"=>"",
            "longitude"=>"",
            "user_id"=>"required",
            "township_id"=> "",
            "title"=> "required",
            "address"=> "required",
            "is_default"=>""

        ];
    }
}
