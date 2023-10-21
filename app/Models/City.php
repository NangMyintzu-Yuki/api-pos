<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "name",
        "division_id",
        "status",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by"
    ];
    public $timestamps = false;
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function township()
    {
        return $this->hasMany(Township::class);
    }
    public function user()
    {
        return $this->hasMany(User::class);
    }

}
