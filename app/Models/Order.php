<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone',
        'customer_email', 'customer_address',
        'subtotal', 'discount_amount', 'coupon_code',
        'shipping_fee', 'total_price', 'status', 'payment_method'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
