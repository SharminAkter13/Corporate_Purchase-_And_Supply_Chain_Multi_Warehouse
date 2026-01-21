<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

       Supplier::insert([
            ['code'=>'SUP-001','name'=>'ABC Supplier','phone'=>'01715248411','created_at' => $now,'updated_at' => $now],
            ['code'=>'SUP-002','name'=>'XYZ Supplier','phone'=>'01734854652','created_at' => $now,'updated_at' => $now],
        ]);
    }
}
