<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "name",
        "category_id",
        "image",
        "price",
        "branch_id",
        "today_menu",
        "special_menu",
        "description",
        "status",
        "created_by",
        "updated_by",
        "deleted_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    public $timestamps = false;
    public function operator()
    {
        return $this->hasMany(Operator::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function product_image()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function sale_detail()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function add_to_cart_detail()
    {
        return $this->hasMany(AddToCartDetail::class);
    }
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients');
    }
    public function dashboard()
    {
        return $this->hasMany(Dashboard::class);
    }
}
