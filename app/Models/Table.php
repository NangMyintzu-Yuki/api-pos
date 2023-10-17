<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "table_no",
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
    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
    
}
