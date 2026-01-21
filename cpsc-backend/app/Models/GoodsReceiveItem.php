<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiveItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'goods_receive_id','purchase_order_item_id','product_id','qty_received'
    ];

    public function purchaseOrderItem() {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function goodsReceive() {
        return $this->belongsTo(GoodsReceive::class);
    }
}

