<?php

namespace App\Contracts;

/**
 * Interface for discount application strategies
 *
 * This interface defines the contract for classes that determine how multiple
 * discounts should be applied to a cart (e.g., best discount only, combine all discounts,
 * apply in priority order, etc.). Following the Strategy pattern, these strategies
 * encapsulate different algorithms for applying multiple discounts.
 */
interface DiscountStrategy
{
    /**
     * Apply a strategy to calculate the final discount from multiple applicable discounts
     *
     * @param Discount[] $applicableDiscounts Array of discounts to evaluate
     * @param Cart $cart The cart to apply discounts to
     * @param User|null $user The user, if any
     * @return float The final calculated discount amount
     */
    public function applyDiscounts(array $applicableDiscounts, Cart $cart, ?User $user): float;
}
