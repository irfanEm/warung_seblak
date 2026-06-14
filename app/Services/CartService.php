<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Shared cart logic for both Customer (session-based) and POS (property-based) flows.
 * All prices are integer cents. No model or session dependencies — pure array manipulation.
 */
class CartService
{
    /**
     * Add an item to the cart. If an identical item (same menu_id, sorted toppings, spiciness, price)
     * already exists, increment its quantity instead of adding a new entry.
     *
     * @param array $cart  Current cart array (keyed by string).
     * @param array $item  Item data: menu_id, name, price, quantity, toppings, spiciness_level, subtotal.
     * @return array       Updated cart array.
     */
    public function addItem(array $cart, array $item): array
    {
        $existingKey = $this->findExistingKey($cart, $item);

        if ($existingKey !== null) {
            $cart[$existingKey]['quantity'] += $item['quantity'];
            $cart[$existingKey]['subtotal'] = $this->calculateSubtotal($cart[$existingKey]);
        } else {
            $cart[uniqid()] = $item;
        }

        return $cart;
    }

    /**
     * Remove an item from the cart by its key.
     */
    public function removeItem(array $cart, string $key): array
    {
        unset($cart[$key]);
        return $cart;
    }

    /**
     * Update the quantity of a specific cart item. Remove if quantity < 1.
     */
    public function updateQuantity(array $cart, string $key, int $quantity): array
    {
        if (!isset($cart[$key])) {
            return $cart;
        }

        if ($quantity < 1) {
            return $this->removeItem($cart, $key);
        }

        $cart[$key]['quantity'] = $quantity;
        $cart[$key]['subtotal'] = $this->calculateSubtotal($cart[$key]);

        return $cart;
    }

    /**
     * Calculate the subtotal for a single cart item: (base price + topping prices) × quantity.
     */
    public function calculateSubtotal(array $item): int
    {
        $unitPrice = (int) $item['price'];

        foreach ($item['toppings'] as $topping) {
            $unitPrice += (int) $topping['price'];
        }

        return $unitPrice * (int) $item['quantity'];
    }

    /**
     * Sum all item subtotals in the cart.
     */
    public function calculateTotal(array $cart): int
    {
        return (int) collect($cart)->sum('subtotal');
    }

    /**
     * Find an existing cart item key that matches the given item's menu_id, toppings, spiciness, and price.
     */
    private function findExistingKey(array $cart, array $item): ?string
    {
        $targetTopIds = collect($item['toppings'])->pluck('id')->sort()->values()->toArray();
        $targetSpiceId = $item['spiciness_level']['id'] ?? null;

        foreach ($cart as $key => $existing) {
            if ((int) $existing['menu_id'] !== (int) $item['menu_id']) {
                continue;
            }

            $existTopIds = collect($existing['toppings'])->pluck('id')->sort()->values()->toArray();
            $existSpiceId = $existing['spiciness_level']['id'] ?? null;

            if ($targetTopIds === $existTopIds && $targetSpiceId === $existSpiceId) {
                return $key;
            }
        }

        return null;
    }
}
