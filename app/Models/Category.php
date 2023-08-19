<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "parent_id",
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
    public function operator()
    {
        return $this->hasMany(Operator::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function product_image()
    {
        return $this->hasMany(ProductImage::class);
    }
}
