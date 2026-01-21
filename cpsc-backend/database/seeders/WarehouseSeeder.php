<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

       Warehouse::insert([
            ['code'=>'WH-DHK','name'=>'Dhaka Warehouse','created_at' => $now, 'updated_at' => $now],
            ['code'=>'WH-CTG','name'=>'Chittagong Warehouse','created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
