{{-- @extends('layouts.app')

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
@endsection --}}


@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <div>
            <span class="me-2">Selamat datang, Admin!</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="card-text display-4">{{ \App\Models\Product::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Pendapatan Hari Ini</h5>
                    <p class="card-text display-4">Rp {{ number_format(\App\Models\Sale::whereDate('sale_date', today())->sum('total_price'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Stok Rendah</h5>
                    <p class="card-text display-4">{{ \App\Models\Product::where('stock', '<', 2)->count() }}</p>
                </div>
            </div>
        </div>
    </div>


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


    <!-- Low Stock Products -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Produk dengan Stok Rendah</h5>
        </div>
        <div class="card-body">
            @if($lowStockProducts->isEmpty())
                <div class="alert alert-success">Tidak ada produk dengan stok rendah!</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    <td class="{{ $product->stock < 2 ? 'text-danger fw-bold' : '' }}">{{ $product->stock }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Notifications -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Notifikasi Terbaru</h5>
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <div class="alert alert-success">Tidak ada notifikasi baru!</div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notification->type == 'low_stock' ? 'Stok Rendah' : 'Pendapatan Tinggi' }}</h6>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $notification->message }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


</div>
@endsection
