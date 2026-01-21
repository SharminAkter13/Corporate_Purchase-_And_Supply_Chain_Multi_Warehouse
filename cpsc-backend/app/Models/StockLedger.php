<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $fillable = [
        'warehouse_id','product_id','ref_type','ref_id',
        'qty_in','qty_out','balance_after'
    ];

    
public function warehouse() {
    return $this->belongsTo(Warehouse::class);
}

public function product() {
    return $this->belongsTo(Product::class);
}

public function reference() {
    return $this->morphTo(null, 'ref_type', 'ref_id');
}
}

