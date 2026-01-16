<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'phone',
        'address'
    ];

    // relasi customer memiliki banyak sale
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // validasi customer apakah member atau tidak
    public function isMember(): bool
    {
        return $this->status === 'Member';
    }

    // presentase diskon
    public function getDiscountPercentage(): int
    {
        return $this->isMember() ? 10 : 0;
    }
}
