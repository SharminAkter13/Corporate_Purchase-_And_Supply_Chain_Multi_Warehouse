<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        $query = Product::query();
        if ($request->has('q')) {
            $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('sku', 'like', "%{$request->q}%");
        }
        return response()->json(['success' => true,'message' => 'OK', 'data' => $query->paginate(10)]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required|string',
            'uom' => 'required|in:pcs,kg,bag'
        ]);
        return response()->json(['success' => true,'message' => 'OK', 'data' => Product::create($data)], 201);
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
