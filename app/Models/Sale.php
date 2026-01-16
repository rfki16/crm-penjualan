<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'sale_date',
        'subtotal',
        'discount',
        'total'
    ];

    protected $casts = [
        'sale_date' => 'date'
    ];

    // relasi sale belongs to customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // relasi sale memiliki banyak saleitems
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // hitung total dari items
    public function calculateTotals(): void
    {
        // hitung sub total
        $this->subtotal = $this->saleItems->sum('subtotal');

        // hitung diskon
        $discountPercentage = $this->customer->getDiscountPercentage();
        $this->discount = $this->subtotal * ($discountPercentage / 100);

        // hitung total akhir
        $this->total = $this->subtotal - $this->discount;
    }

    // format total
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
