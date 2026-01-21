<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->has('q')) {
            $query->where('name', 'like', "%{$request->q}%")
                  ->orWhere('code', 'like', "%{$request->q}%");
        }
        return response()->json(['success' => true,'message' => 'OK', 'data' => $query->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:suppliers,code',
            'name' => 'required|string',
            'phone' => 'nullable'
        ]);
        return response()->json(['success' => true,'message' => 'OK', 'data' => Supplier::create($data)], 201);
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
