<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddToCart extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "date",
        "total_amount",
        "user_id",
        "status",
        "created_by",
        "updated_by",
        "deleted_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function add_to_cart_detail()
    {
        return $this->hasMany(AddToCartDetail::class);
    }

}
