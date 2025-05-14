@extends('layouts.app')

@section('title', 'Detail Produk')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Detail Produk</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if ($product->image)
                                    <img src="{{ asset('storage/product_images/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-fluid rounded mb-3" style="max-height: 300px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <span class="text-muted">No image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h2>{{ $product->name }}</h2>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Harga Awal</th>
                                        <td>Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Jual</th>
                                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Stok Tersedia</th>
                                        <td class="{{ $product->stock < 2 ? 'text-danger fw-bold' : '' }}">
                                            {{ $product->stock }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keuntungan per Unit</th>
                                        <td>Rp
                                            {{ number_format($product->selling_price - $product->base_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Potensi Keuntungan</th>
                                        <td>Rp
                                            {{ number_format(($product->selling_price - $product->base_price) * $product->stock, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                                <h5 class="mt-4">Deskripsi</h5>
                                <p>{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary ms-2">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
