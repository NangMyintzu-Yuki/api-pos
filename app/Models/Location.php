<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
            "latitude",
            "longitude",
            "user_id",
            "created_at",
            "updated_at",
            "deleted_at",
    ];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
