<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
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
    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients');
    }
}
