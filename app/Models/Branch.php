<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "name",
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
    public function category()
    {
        return $this->hasMany(Category::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function product_image()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function table()
    {
        return $this->hasMany(Table::class);
    }
    public function sale()
    {
        return $this->hasMany(Sale::class);
    }

}
