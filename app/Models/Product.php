<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'selling_price',
        'stock',
        'image'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function reduceStock($quantity)
{
    if ($this->stock < $quantity) {
        throw new \Exception('Stok tidak mencukupi');
    }

    $this->decrement('stock', $quantity);

    // Buat notifikasi jika stok kurang dari threshold
    if ($this->stock < 2) {
        Notification::create([
            'type' => 'low_stock',
            'message' => "Stok produk {$this->name} kurang dari 2, perlu ditambah!"
        ]);
    }

    return $this;
}
}

