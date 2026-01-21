<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

       Product::insert([
            ['sku'=>'P-1001','name'=>'Cement','uom'=>'bag','created_at' => $now,'updated_at' => $now],
            ['sku'=>'P-1002','name'=>'Steel Rod','uom'=>'kg','created_at' => $now,'updated_at' => $now],
            ['sku'=>'P-1003','name'=>'Bricks','uom'=>'pcs','created_at' => $now,'updated_at' => $now],
        ]);
    }
}
