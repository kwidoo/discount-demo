<?php

namespace App\Conditions;

use App\Contracts\DiscountCondition;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Condition that checks if the cart total meets a minimum value
 *
 * This class implements the DiscountCondition interface to restrict
 * discounts to carts with a minimum total value.
 */
class CartTotalCondition implements DiscountCondition
{
    /**
     * Create a new cart total condition
     *
     * @param float $minTotal The minimum cart total required
     */
    public function __construct(protected float $minTotal) {}

    /**
     * Check if the condition is satisfied
     *
     * @param Cart $cart The cart to check the total of
     * @param User|null $user The user (not used in this condition)
     * @return bool True if cart total is at least the minimum required
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        return $cart->total() >= $this->minTotal;
    }

    /**
     * Get the minimum total value required
     *
     * @return float The minimum cart total
     */
    public function getMinTotal(): float
    {
        return $this->minTotal;
    }
}
