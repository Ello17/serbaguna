<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Events\StockUpdated; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::transaction(function () use ($product, $request) {
                // Kurangi stok
                $product->reduceStock($request->quantity);

                // Catat penjualan
                Sale::create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'total_price' => $product->selling_price * $request->quantity,
                    'sale_date' => now(),
                ]);

                // Dispatch event di dalam transaction
                event(new StockUpdated($product));
            });

            return redirect()->back()->with('success', 'Pembelian berhasil!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
