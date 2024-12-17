<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
    ];

    // Boot method to calculate total price before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->calculateTotalPrice();
        });

        static::updating(function ($order) {
            $order->calculateTotalPrice();
        });
    }

    // Function to calculate total price
    public function calculateTotalPrice()
    {
        if ($this->product) {
            $this->total_price = $this->quantity * $this->product->price;
        }
    }

    // Define relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
