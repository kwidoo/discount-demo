<?php

namespace App\Strategies;

use App\Contracts\Discount;
use App\Contracts\DiscountStrategy;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Strategy that combines multiple applicable discounts
 *
 * This strategy adds together all applicable discounts that
 * are marked as combinable, allowing multiple discounts to
 * be applied simultaneously.
 */
class CombinableDiscountsStrategy implements DiscountStrategy
{
    /**
     * Apply all combinable discounts to get a total discount amount
     *
     * @param Discount[] $applicableDiscounts Array of discounts to evaluate
     * @param Cart $cart The cart to apply discounts to
     * @param User|null $user The user, if any
     * @return float The sum of all applicable combinable discounts
     */
    public function applyDiscounts(array $applicableDiscounts, Cart $cart, ?User $user): float
    {
        $totalDiscount = 0;
        foreach ($applicableDiscounts as $discount) {
            if ($discount->isApplicable($cart, $user) && $discount->isCombinable()) {
                $totalDiscount += $discount->calculate($cart, $user);
            }
        }
        return $totalDiscount;
    }
}
