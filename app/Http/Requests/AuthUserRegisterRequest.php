<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthUserRegisterRequest extends FormRequest
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
            "name"=>"required",
            // "phone_no"=>"required|unique:users,phone_no",
            "phone_no"=>["required", Rule::unique('users', 'phone_no')->where(fn ($query) => $query->whereNull('deleted_at'))],
            "address"=>"",
            "password"=>"required"
        ];
    }
}
