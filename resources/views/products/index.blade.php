@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Produk</h2>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i> Impor
            </button>
            <a href="{{ route('products.export') }}" class="btn btn-info">
                <i class="bi bi-download"></i> Ekspor
            </a>
        </div>
    </div>

    <!-- Potential Revenue Card -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Potensi Pendapatan</h5>
        </div>
        <div class="card-body">
            <p class="display-6 mb-0">Rp {{ number_format($totalPotentialRevenue, 0, ',', '.') }}</p>
            <p class="text-muted mb-0">Total pendapatan jika semua produk terjual</p>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">Daftar Produk</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga Awal</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="{{ $product->stock < 2 ? 'text-danger fw-bold' : '' }}">{{ $product->stock }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada produk ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Impor Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih file Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls" required>
                        <div class="form-text">Format file harus Excel (.xlsx, .xls)</div>
                    </div>
                    <div class="alert alert-info">
                        <strong>Petunjuk:</strong> Unduh template <a href="{{ asset('templates/product_template.xlsx') }}" download>di sini</a> untuk memastikan format yang benar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
