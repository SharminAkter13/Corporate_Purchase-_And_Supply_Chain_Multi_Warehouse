<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku','name','uom'];

    public function stocks() {
        return $this->hasMany(Stock::class);
    }
}

