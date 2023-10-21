<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        "status",
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public $timestamps = false;
    public function city()
    {
        return $this->hasMany(City::class);
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
