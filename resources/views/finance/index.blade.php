@extends('layouts.app')

@section('title', 'Manajemen Keuangan')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Keuangan</h2>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download"></i> Ekspor Laporan
            </button>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filter Tanggal</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">Lihat Pendapatan Harian</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate }}" max="{{ today()->toDateString() }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="form-label">Periode Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="form-label">Periode Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" max="{{ today()->toDateString() }}">
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daily Summary -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ringkasan Harian - {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pendapatan Hari Ini</h5>
                            <p class="card-text display-5">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Keuntungan Hari Ini</h5>
                            <p class="card-text display-5">Rp {{ number_format($dailyProfit, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Produk Terjual</h5>
                            <p class="card-text display-5">{{ $dailySales->sum('quantity') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($dailySales->isEmpty())
                <div class="alert alert-info">Tidak ada penjualan pada tanggal ini.</div>
            @else
                <h5 class="mt-4">Detail Penjualan</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Kuantitas</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                                <th>Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailySales as $sale)
                                <tr>
                                    <td>{{ $sale->product->name }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>Rp {{ number_format($sale->product->selling_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format(($sale->product->selling_price - $sale->product->base_price) * $sale->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th>Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</th>
                                <th>Rp {{ number_format($dailyProfit, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Ringkasan Periode - {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} sampai {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Pendapatan</h5>
                            <p class="card-text display-5">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Keuntungan</h5>
                            <p class="card-text display-5">Rp {{ number_format($monthlyProfit, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="mt-4">Grafik Pendapatan Harian</h5>
            <div class="chart-container" style="position: relative; height:300px; width:100%">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Ekspor Laporan Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.export') }}" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="export_start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="export_start_date" name="start_date" value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="export_end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="export_end_date" name="end_date" value="{{ $endDate }}" max="{{ today()->toDateString() }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ekspor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Pendapatan Harian',
                    data: @json($chartData['data']),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
