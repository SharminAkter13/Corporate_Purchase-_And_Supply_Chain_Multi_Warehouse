<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    protected $fillable = ['pr_no','warehouse_id','pr_date','status','note'];

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function items() {
        return $this->hasMany(PurchaseRequisitionItem::class);
    }
}

