<?php

namespace App\Conditions;

use App\Contracts\Cart;
use App\Contracts\DiscountCondition;
use App\Contracts\User;

class ByProductIdCondition implements DiscountCondition
{
    /**
     * Create a new condition that checks if the cart contains a minimum quantity of a specific product.
     *
     * @param int $productId   The product ID to check for
     * @param int $minQuantity The minimum quantity required (default: 1)
     */
    public function __construct(
        protected int $productId,
        protected int $minQuantity = 1
    ) {}

    /**
     * Check if the condition is satisfied.
     *
     * @param Cart $cart
     * @param User|null $user
     * @return bool
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        $quantity = $cart->getProductQuantity($this->productId);

        return $quantity >= $this->minQuantity;
    }

    /**
     * Get the product ID this condition checks for.
     *
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Get the minimum quantity required.
     *
     * @return int
     */
    public function getMinQuantity(): int
    {
        return $this->minQuantity;
    }
}
