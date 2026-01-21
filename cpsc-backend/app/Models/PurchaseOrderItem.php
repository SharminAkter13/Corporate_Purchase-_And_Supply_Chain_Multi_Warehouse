<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'purchase_order_id','product_id','qty_ordered','unit_price','line_total'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function receiveItems() {
        return $this->hasMany(GoodsReceiveItem::class);
    }

    public function totalReceived() {
    return $this->receiveItems()->sum('qty_received');
}
}
