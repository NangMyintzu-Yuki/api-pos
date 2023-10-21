<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "name" => "required",
            "username" => "",
            "email"=>"",
            "phone_no"=>["required","unique:users,phone_no"],
            "password"=>"required",
            "division_id" => "",
            "township_id" => "",
            "city_id" => "",
            "address" =>"required",
            "profile"=>"",
            "user_type" =>"",
            "point"=>"",
            "profile"=>"",
            "status"=>""
        ];
    }
}
