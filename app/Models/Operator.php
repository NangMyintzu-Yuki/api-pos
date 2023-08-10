<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Operator extends Model
{
    use HasFactory,SoftDeletes,HasApiTokens;
    protected $fillable = [
        "role",
        "name",
        "username",
        "password",
        "branch_id",
        "status",
        "created_by",
        "updated_by",
        "deleted_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    public $timestamps = false;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
   public function product()
   {
    return $this->belongsTo(Product::class);
   }
   public function product_images()
   {
    return $this->belongsTo(ProductImage::class);
   }
   public function kitchen()
   {
    return $this->hasMany(Kitchen::class);
   }
}
