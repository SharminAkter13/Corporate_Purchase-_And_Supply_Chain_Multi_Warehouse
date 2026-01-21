<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{

    const STATUS_DRAFT = 'draft';
    const STATUS_APPROVED = 'approved';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'po_no','supplier_id','warehouse_id','pr_id','po_date','status','note'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceives() {
        return $this->hasMany(GoodsReceive::class);
    }
    public function purchaseRequisition() {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }

    
    public function isApproved() {
        return $this->status === self::STATUS_APPROVED;
    }
}
