<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Notification;

class HomeController extends Controller
{
  public function dashboard()
{
    $lowStockProducts = Product::where('stock', '<', 2)->get();
    $notifications = Notification::where('read', false)->latest()->take(5)->get();
    $products = Product::latest()->paginate(6); // Menampilkan 6 produk terbaru

    return view('dashboard', compact('lowStockProducts', 'notifications', 'products'));
}

}
