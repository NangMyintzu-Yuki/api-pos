<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            "voucher_no"=>"required|unique:sales,voucher_no",
            "date"=>"required",
            "total_amount"=>"required",
            "user_id"=>"",
            "table_id"=>"",
            "user_qty"=>"",
            "order_type"=>"",
            "status"=>"",
        ];
    }
}
