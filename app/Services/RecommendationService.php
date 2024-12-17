<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Suggest products based on collaborative filtering.
     *
     * @param int $customerId
     * @return Collection
     */
    public function suggestProductsForCustomer(int $customerId): Collection
    {
        // 1. Fetch customer orders
        $customerOrders = Order::where('customer_id', $customerId)
            ->pluck('product_id')
            ->unique()
            ->toArray();

        // 2. Find other customers who bought similar products
        $similarCustomers = Order::whereIn('product_id', $customerOrders)
            ->where('customer_id', '!=', $customerId)
            ->pluck('customer_id')
            ->unique();

        // 3. Find products purchased by similar customers
        $recommendedProducts = Order::whereIn('customer_id', $similarCustomers)
            ->whereNotIn('product_id', $customerOrders) // Exclude already purchased products
            ->pluck('product_id')
            ->unique();

        // 4. Fetch product details
        return Product::whereIn('id', $recommendedProducts)->get();
    }
}
