<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{

    public function index()
    {
        $pos = PurchaseOrder::with('items', 'supplier', 'warehouse')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $pos
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prId' => 'required|exists:purchase_requisitions,id',
            'supplierId' => 'required|exists:suppliers,id',
            'poDate' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|gt:0',
            'items.*.unitPrice' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error', 'data' => $validator->errors()], 422);
        }

        $pr = PurchaseRequisition::findOrFail($request->prId);

        // ____PO from approve PR____

        if ($pr->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'PO can only be created from an Approved PR'], 422);
        }

        return DB::transaction(function () use ($request, $pr) {
            $po = PurchaseOrder::create([
                'po_no' => 'PO-' . strtoupper(uniqid()),
                'supplier_id' => $request->supplierId,
                'warehouse_id' => $pr->warehouse_id, 
                'pr_id' => $pr->id,
                'po_date' => $request->poDate,
                'status' => 'draft'
            ]);

            foreach ($request->items as $item) {
                $po->items()->create([
                    'product_id' => $item['productId'],
                    'qty_ordered' => $item['qty'],
                    'unit_price' => $item['unitPrice'],
                    'line_total' => $item['qty'] * $item['unitPrice']
                ]);
            }

            return response()->json(['success' => true, 'message' => 'OK', 'data' => $po->load('items')], 201);
        });
    }
    public function show($id)
    {
        $po = PurchaseOrder::with(['warehouse', 'supplier', 'items.product'])->findOrFail($id);
        
        return response()->json([
            'success' => true, 
            'message' => 'OK', 
            'data' => $po
        ]);
    }

    public function approve($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Invalid status transition'], 409);
        }

        $po->update(['status' => 'approved']);
        return response()->json(['success' => true, 'message' => 'OK', 'data' => $po]);
    }

    public function update(Request $request, $id)
    {
        $po = PurchaseOrder::findOrFail($id);

        //  _____Approved PO modified logic_____
        
        if ($po->status === 'approved') {
            return response()->json([
                'success' => false, 
                'message' => 'Strict Rule violation: Approved PO cannot be modified.'
            ], 409);
        }

        
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status === 'approved') {
            return response()->json([
                'success' => false, 
                'message' => 'Strict Rule violation: Approved PO cannot be deleted.'
            ], 409);
        }

        $po->delete();
        return response()->json(['success' => true, 'message' => 'OK']);
    }
}