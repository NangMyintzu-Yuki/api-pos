<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Township extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "name",
        "city_id",
        "status",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];
    public $timestamps = false;
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function location()
    {
        return $this->hasMany(Location::class);
    }

}
