<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['purchase_requisition_id','product_id','qty_requested'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
