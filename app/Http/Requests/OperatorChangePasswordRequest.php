<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperatorChangePasswordRequest extends FormRequest
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
            // 'operator_id' => ['required', Rule::exists('operators','id')->where(function($query){
            //     return $query->where('deleted_at',null);
            // })],
            'operator_id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required'

        ];
    }
}
