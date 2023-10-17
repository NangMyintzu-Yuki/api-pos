<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            "branch_id"=>"required",
            "payment_type_id"=>"required",
            // "sale_id"=>"required|unique:payments,sale_id",
            "sale_id" => [
                "required",
                "not_in:null",
                Rule::unique('payments', 'sale_id')->where(fn ($query) => $query->whereNull('deleted_at'))
            ],
            "cash_collected_by"=>"required",
            "status"=>"",
        ];
    }

    public function messages()
    {
        return [
            'sale_id.unique' => 'Already paid for this voucher no!',
        ];
    }
}
