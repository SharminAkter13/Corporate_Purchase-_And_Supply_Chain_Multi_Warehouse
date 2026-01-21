<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceive;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GoodsReceiveController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchaseOrderId' => 'required|exists:purchase_orders,id',
            'receivedDate' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.poItemId' => 'required|exists:purchase_order_items,id',
            'items.*.qtyReceived' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error', 'data' => $validator->errors()], 422);
        }

        $po = PurchaseOrder::findOrFail($request->purchaseOrderId);

        //  ____must be approved PO_____

        if ($po->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Cannot receive items against a non-approved PO'], 422);
        }

        return DB::transaction(function () use ($request, $po) {
            $grn = GoodsReceive::create([
                'grn_no' => 'GRN-' . strtoupper(uniqid()),
                'purchase_order_id' => $po->id,
                'received_date' => $request->receivedDate
            ]);

            foreach ($request->items as $itemData) {
                $poItem = PurchaseOrderItem::findOrFail($itemData['poItemId']);
                
                // ____Over-receive prevention____

                $alreadyReceived = $poItem->receiveItems()->sum('qty_received');
                $remaining = $poItem->qty_ordered - $alreadyReceived;

                if ($itemData['qtyReceived'] > $remaining) {
                    throw new \Exception("Over-receive not allowed for product: {$poItem->product->name}. Remaining: {$remaining}");
                }

                // ____Create GRN Item_____
                $grn->items()->create([
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $poItem->product_id,
                    'qty_received' => $itemData['qtyReceived']
                ]);

                // _____Concurrency & lockForUpdate_____

                $stock = Stock::where('warehouse_id', $po->warehouse_id)
                    ->where('product_id', $poItem->product_id)
                    ->lockForUpdate()
                    ->firstOrCreate([
                        'warehouse_id' => $po->warehouse_id,
                        'product_id' => $poItem->product_id
                    ], ['qty_on_hand' => 0]);

                $stock->qty_on_hand += $itemData['qtyReceived'];
                $stock->save();

                // ____Recorded Stock Ledger_____
                
                StockLedger::create([
                    'warehouse_id' => $po->warehouse_id,
                    'product_id' => $poItem->product_id,
                    'ref_type' => 'GRN',
                    'ref_id' => $grn->id,
                    'qty_in' => $itemData['qtyReceived'],
                    'qty_out' => 0,
                    'balance_after' => $stock->qty_on_hand
                ]);
            }

            return response()->json(['success' => true, 'message' => 'OK', 'data' => $grn->load('items')], 201);
        });
    }

    public function receivingSummary($id)
    {
        $po = PurchaseOrder::with(['items.product', 'items.receiveItems'])->findOrFail($id);

        $summary = $po->items->map(function ($item) {
            $receivedTotal = $item->receiveItems->sum('qty_received');
            $status = 'PENDING';
            if ($receivedTotal > 0 && $receivedTotal < $item->qty_ordered) $status = 'PARTIAL';
            if ($receivedTotal >= $item->qty_ordered) $status = 'COMPLETE';

            return [
                'product' => $item->product->name,
                'ordered_qty' => $item->qty_ordered,
                'received_total' => $receivedTotal,
                'remaining_qty' => max(0, $item->qty_ordered - $receivedTotal),
                'status' => $status
            ];
        });

        return response()->json(['success' => true, 'message' => 'OK', 'data' => $summary]);
    }
}