<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "sale_id"=>$this->sale()->first(),
            "product_id"=>$this->product()->first(),
            "price"=>$this->price,
            "quantity"=>$this->quantity,
            "amount"=>$this->amount,
            "status"=>$this->status,
            "created_at"=>$this->created_at,
            "updated_at"=>$this->updated_at,
            "deleted_at"=>$this->deleted_at,
            "created_by"=>$this->created_by,
            "updated_by"=>$this->updated_by,
            "deleted_by"=>$this->deleted_by,
        ];
    }
}
