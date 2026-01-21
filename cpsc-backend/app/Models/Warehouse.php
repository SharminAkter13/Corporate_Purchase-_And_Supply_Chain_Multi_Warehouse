<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['code','name'];

    public function purchaseRequisitions() {
        return $this->hasMany(PurchaseRequisition::class);
    }

    public function purchaseOrders() {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }
}

