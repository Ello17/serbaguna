<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SalesExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::with('product')
            ->whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($sale) {
                return [
                    'Tanggal' => $sale->sale_date,
                    'Nama Produk' => $sale->product->name,
                    'Kuantitas' => $sale->quantity,
                    'Harga Satuan' => $sale->product->selling_price,
                    'Total Harga' => $sale->total_price,
                    'Keuntungan' => ($sale->product->selling_price - $sale->product->base_price) * $sale->quantity,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Kuantitas',
            'Harga Satuan',
            'Total Harga',
            'Keuntungan'
        ];
    }
}
