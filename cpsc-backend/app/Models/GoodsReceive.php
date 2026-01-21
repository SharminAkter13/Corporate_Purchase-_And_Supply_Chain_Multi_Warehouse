<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceive extends Model
{
    protected $fillable = ['grn_no','purchase_order_id','received_date','note'];

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function items() {
        return $this->hasMany(GoodsReceiveItem::class);
    }
}

