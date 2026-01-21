<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\WarehouseController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\PurchaseRequisitionController;
use App\Http\Controllers\Api\V1\PurchaseOrderController;
use App\Http\Controllers\Api\V1\GoodsReceiveController;
use App\Http\Controllers\Api\V1\StockController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    
    // ____Warehouses____
    
    Route::get('warehouses', [WarehouseController::class, 'index']);
    Route::post('warehouses', [WarehouseController::class, 'store']);
    Route::get('warehouses/{id}', [WarehouseController::class, 'show']);
    Route::put('warehouses/{id}', [WarehouseController::class, 'update']);
    Route::delete('warehouses/{id}', [WarehouseController::class, 'destroy']);

    // _____Suppliers_____

    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::post('suppliers', [SupplierController::class, 'store']);
    Route::get('suppliers/{id}', [SupplierController::class, 'show']);
    Route::put('suppliers/{id}', [SupplierController::class, 'update']);
    Route::delete('suppliers/{id}', [SupplierController::class, 'destroy']);

    // ____Products_____

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    
    // ____ PR Flow ____

    Route::get('pr', [PurchaseRequisitionController::class, 'index']);      
    Route::get('pr/{id}', [PurchaseRequisitionController::class, 'show']);
    Route::post('pr', [PurchaseRequisitionController::class, 'store']);
    Route::post('pr/{id}/approve', [PurchaseRequisitionController::class, 'approve']);

    
    // ____ PO Flow ____

    Route::get('po', [PurchaseOrderController::class, 'index']);
    Route::post('po', [PurchaseOrderController::class, 'store']);
    Route::get('po/{id}', [PurchaseOrderController::class, 'show']);
    Route::put('po/{id}', [PurchaseOrderController::class, 'update']);
    Route::delete('po/{id}', [PurchaseOrderController::class, 'destroy']);
    
    // ____PO Routes____

    Route::post('po/{id}/approve', [PurchaseOrderController::class, 'approve']);
    Route::get('po/{id}/receiving-summary', [GoodsReceiveController::class, 'receivingSummary']);

    
    // ___ Inventory & GRN ____

    Route::post('grn', [GoodsReceiveController::class, 'store']);
    Route::get('stocks', [StockController::class, 'index']);
});