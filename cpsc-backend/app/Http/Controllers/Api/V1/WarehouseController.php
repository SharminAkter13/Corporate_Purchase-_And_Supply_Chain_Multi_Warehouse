<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return response()->json(['success' => true, 'message' => 'OK','data' => Warehouse::all()]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => 'required|unique:warehouses,code',
            'name' => 'required|string|max:255'
        ]);
        return response()->json(['success' => true,'message' => 'OK', 'data' => Warehouse::create($data)], 201);
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