<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddToCartDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "add_to_cart_id",
        "product_id",
        "quantity",
        "amount",
        "status",
        "created_by",
        "updated_by",
        "deleted_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    public function add_to_cart()
    {
        return $this->belongsTo(AddToCart::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
