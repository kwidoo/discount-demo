<?php

namespace App\Strategies;

use App\Contracts\Discount;
use App\Contracts\DiscountStrategy;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Strategy that applies discounts in order of priority
 *
 * This strategy sorts discounts by priority and applies them in order,
 * stopping after the first non-combinable discount is encountered.
 * It also provides detailed information about which discounts were applied.
 */
class OrderedDiscountStrategy implements DiscountStrategy
{
    /**
     * Information about which discounts were applied
     *
     * @var array
     */
    protected array $appliedDiscounts = [];

    /**
     * Apply discounts in order of priority and exit on first non-combinable discount
     *
     * @param Discount[] $applicableDiscounts Array of discounts to evaluate
     * @param Cart $cart The cart to apply discounts to
     * @param User|null $user The user, if any
     * @return float The total discount amount
     */
    public function applyDiscounts(array $applicableDiscounts, Cart $cart, ?User $user): float
    {
        // Reset applied discounts before each calculation
        $this->appliedDiscounts = [];

        // Sort discounts by priority (lower number = higher priority)
        usort($applicableDiscounts, function (Discount $a, Discount $b) {
            return $a->getPriority() - $b->getPriority();
        });

        $totalDiscount = 0;

        foreach ($applicableDiscounts as $discount) {
            // Check if discount is applicable
            if (!$discount->isApplicable($cart, $user)) {
                continue;
            }

            // Calculate and add discount
            $discountAmount = $discount->calculate($cart, $user);
            $totalDiscount += $discountAmount;
            $this->appliedDiscounts[] = [
                'code' => $discount->getCode(),
                'amount' => $discountAmount,
                'combinable' => $discount->isCombinable(),
            ];

            // If discount is not combinable, exit after applying
            if (!$discount->isCombinable()) {
                break;
            }
        }

        return $totalDiscount;
    }

    /**
     * Get the details of discounts that were applied
     *
     * @return array Information about applied discounts
     */
    public function getAppliedDiscounts(): array
    {
        return $this->appliedDiscounts;
    }
}
