<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_value',
        'max_uses',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Kiểm tra coupon còn hiệu lực với tổng tiền đơn hàng.
     */
    public function isValidForTotal(float $subtotal): bool
    {
        $now = Carbon::now();

        if (!$this->is_active) {
            return false;
        }

        if (!is_null($this->starts_at) && $now->lt($this->starts_at)) {
            return false;
        }

        if (!is_null($this->expires_at) && $now->gt($this->expires_at)) {
            return false;
        }

        if ($subtotal < $this->min_order_value) {
            return false;
        }

        if (!is_null($this->max_uses) && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Tính số tiền giảm dựa trên subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValidForTotal($subtotal)) {
            return 0.0;
        }

        if ($this->type === 'percent') {
            return round($subtotal * ($this->value / 100), 0);
        }

        // fixed amount
        return min($this->value, $subtotal);
    }
}


