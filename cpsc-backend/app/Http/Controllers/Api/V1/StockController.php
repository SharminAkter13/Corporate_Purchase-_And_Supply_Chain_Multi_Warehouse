<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['product', 'warehouse']);
        
        if ($request->warehouseId) $query->where('warehouse_id', $request->warehouseId);
        if ($request->productId) $query->where('product_id', $request->productId);
        if ($request->q) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%");
            });
        }

        return response()->json(['success' => true,'message' => 'OK', 'data' => $query->get()]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
