<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'total_amount',
        'status',
        'receipt_path',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}