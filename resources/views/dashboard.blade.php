@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <!-- ... bagian sebelumnya ... -->

        <!-- Produk Terbaru -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Produk</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if ($product->image)
                                    <img src="{{ asset('storage/product_images/' . $product->image) }}" class="card-img-top"
                                        alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">
                                        <strong>Harga:</strong> Rp
                                        {{ number_format($product->selling_price, 0, ',', '.') }}<br>
                                        <strong>Stok:</strong> {{ $product->stock }}<br>
                                        <small class="text-muted">{{ Str::limit($product->description, 100) }}</small>
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <form action="{{ route('products.purchase', $product->id) }}" method="POST"
                                        class="row g-2">
                                        @csrf
                                        <div class="col-6">
                                            <input type="number" name="quantity" class="form-control" value="1"
                                                min="1" max="{{ $product->stock }}" required>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="bi bi-cart-plus"></i> Beli
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-2 d-grid">
                                        <a href="{{ route('products.show', $product->id) }}"
                                            class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- ... bagian notifikasi ... -->
    </div>
@endsection
