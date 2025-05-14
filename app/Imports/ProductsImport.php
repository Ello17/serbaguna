<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new Product([
            'name'        => $this->getValue($row, ['nama_produk', 'name']),
            'description' => $this->getValue($row, ['deskripsi', 'description']),
            'base_price'  => $this->convertToNumeric($this->getValue($row, ['harga_awal', 'base_price'])),
            'selling_price'=> $this->convertToNumeric($this->getValue($row, ['harga_jual', 'selling_price'])),
            'stock'       => $this->convertToInteger($this->getValue($row, ['stok', 'stock'])),
            'image'       => $this->getValue($row, ['gambar', 'image']) ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_produk' => 'required|max:255',
            '*.name' => 'required_without:*.nama_produk|max:255',
            '*.harga_awal' => 'required|numeric|min:0',
            '*.base_price' => 'required_without:*.harga_awal|numeric|min:0',
            '*.harga_jual' => 'required|numeric|min:0',
            '*.selling_price' => 'required_without:*.harga_jual|numeric|min:0',
            '*.stok' => 'required|integer|min:0',
            '*.stock' => 'required_without:*.stok|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi',
            'numeric' => 'Kolom :attribute harus berupa angka',
            'min' => 'Kolom :attribute minimal :min',
            'required_without' => 'Salah satu kolom nama produk/harga/stok harus diisi'
        ];
    }

    // Helper methods
    private function getValue(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($row[$key])) {
                return $row[$key];
            }
        }
        return null;
    }

    private function convertToNumeric($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        return (float) str_replace(['.', ',', 'Rp', ' '], ['', '.', '', ''], $value);
    }

    private function convertToInteger($value)
    {
        return (int) $this->convertToNumeric($value);
    }
}
