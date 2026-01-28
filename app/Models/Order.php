<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'payment_method',
        'total_amount',
        'status',
        'receipt_path',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias for orderItems with product eager loading.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }
}