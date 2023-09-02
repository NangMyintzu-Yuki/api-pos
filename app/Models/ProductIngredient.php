<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductIngredient extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "product_id",
        "ingredient_id",
        "status",
        "created_by",
        "updated_by",
        "deleted_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    public $timestamp = false;
    protected $hidden = [];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class,'ingredient_id');
    }
}
