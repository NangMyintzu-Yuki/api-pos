<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "payment_type_id",
        "sale_id",
        "cash_collected_by",
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
        return $this->belongsTo(Sale::class);
    }
    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }
    public function cash_collector()
    {
        return $this->belongsTo(Operator::class);
    }
}
