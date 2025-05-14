<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->paginate(10);

        $totalPotentialRevenue = Product::sum(DB::raw('selling_price * stock')); // Perbaikan di sini

        return view('products.index', compact('products', 'totalPotentialRevenue'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'base_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create($validated);

        // Check for low stock
        if ($product->stock < 2) {
            Notification::create([
                'type' => 'low_stock',
                'message' => "Stok produk {$product->name} kurang dari 2, perlu ditambah!"
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'base_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $gambar = $request->file('image');
            $fileName = $gambar->getClientOriginalName();
            $gambar->storeAs('product_images', $fileName);
            $validated['image'] = $fileName;
        }

        $product->update($validated);

        // Check for low stock
        if ($product->stock < 2) {
            Notification::create([
                'type' => 'low_stock',
                'message' => "Stok produk {$product->name} kurang dari 2, perlu ditambah!"
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function export()
    {
        // dd('Exporting products...'); // Debugging line
        return Excel::download(new ProductsExport(), 'products.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $gambar) {
                $fileName = $gambar->getClientOriginalName();
                $gambar->storeAs('product_images', $fileName);
            }
        }

        Excel::import(new ProductsImport(), $request->file('file'));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diimpor!');
    }
}
