<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
            "id"=>"required",
            "branch_id" => "",
            "voucher_no" => "",
            "date" => "",
            "total_amount" => "",
            "user_id" => "",
            "table_id" => "",
            "user_qty" => "",
            "order_type" => "",
            "status" => "",
        ];
    }
}
