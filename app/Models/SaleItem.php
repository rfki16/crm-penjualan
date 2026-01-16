<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    // sale item belongs to sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // sale item belongs to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // hitung sub total
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->quantity * $this->price;
    }
}
