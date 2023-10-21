<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "id" =>"required",
            "name" => "",
            "username" => "",
            "email" => "",
            "phone_no" => "",
            "password" => "",
            "division_id" => "",
            "city_id" => "",
            "township_id" => "",
            "address" => "",
            "user_type" => "",
            "point" => "",
            "profile" => "",
            "status" => ""
        ];
    }
}
