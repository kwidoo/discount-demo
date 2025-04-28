<?php

namespace App\Strategies;

use App\Contracts\Discount;
use App\Contracts\DiscountStrategy;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Strategy that applies the single best (highest value) discount
 *
 * This strategy evaluates all applicable discounts and applies only
 * the one that gives the highest discount amount, ignoring all others.
 */
class BestDiscountStrategy implements DiscountStrategy
{
    /**
     * Apply the best (highest value) discount from all applicable discounts
     *
     * @param Discount[] $applicableDiscounts Array of discounts to evaluate
     * @param Cart $cart The cart to apply discounts to
     * @param User|null $user The user, if any
     * @return float The highest discount amount found
     */
    public function applyDiscounts(array $applicableDiscounts, Cart $cart, ?User $user): float
    {
        $maxDiscount = 0;
        foreach ($applicableDiscounts as $discount) {
            if ($discount->isApplicable($cart, $user)) {
                $maxDiscount = max($maxDiscount, $discount->calculate($cart, $user));
            }
        }
        return $maxDiscount;
    }
}
