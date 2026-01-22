<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequisition::with(['warehouse', 'items.product']);

        if ($request->warehouseId) {
            $query->where('warehouse_id', $request->warehouseId);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->from && $request->to) {
            $query->whereBetween('pr_date', [$request->from, $request->to]);
        }
        if ($request->q) {
            $query->where('pr_no', 'like', "%{$request->q}%");
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $query->latest()->paginate(10)
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouseId' => 'required|exists:warehouses,id',
            'prDate' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error', 'data' => $validator->errors()], 422);
        }

        // ______logic for  Duplicate product check in  PR______

        $productIds = collect($request->items)->pluck('productId');
        if ($productIds->duplicates()->isNotEmpty()) {
            return response()->json(['success' => false, 'message' => 'Duplicate products found in PR items'], 422);
        }

        return DB::transaction(function () use ($request) {
            $pr = PurchaseRequisition::create([
                'pr_no' => 'PR-' . strtoupper(uniqid()), 
                'warehouse_id' => $request->warehouseId,
                'pr_date' => $request->prDate,
                'status' => 'draft'
            ]);

            foreach ($request->items as $item) {
                $pr->items()->create([
                    'product_id' => $item['productId'],
                    'qty_requested' => $item['qty']
                ]);
            }

            return response()->json(['success' => true, 'message' => 'OK', 'data' => $pr->load('items')], 201);
        });
    }
    public function show($id)
    {
        $pr = PurchaseRequisition::with(['warehouse', 'items.product'])->findOrFail($id);
        
        return response()->json([
            'success' => true, 
            'message' => 'OK', 
            'data' => $pr
        ]);
    }

    public function approve($id)
    {
        $pr = PurchaseRequisition::findOrFail($id);
        
        if ($pr->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Only draft PR can be approved'], 409);
        }

        $pr->update(['status' => 'approved']);
        return response()->json(['success' => true, 'message' => 'OK', 'data' => $pr]);
    }
}