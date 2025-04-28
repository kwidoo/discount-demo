<?php

namespace App\Conditions;

use App\Contracts\DiscountCondition;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Condition that checks if a user is authenticated
 *
 * This class implements the DiscountCondition interface to restrict
 * discounts to authenticated users only.
 */
class AuthenticatedUserCondition implements DiscountCondition
{
    /**
     * Check if the condition is satisfied
     *
     * @param Cart $cart The cart to check
     * @param User|null $user The user to check authentication status
     * @return bool True if user is authenticated, false otherwise
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        return $user !== null && $user->isAuthenticated();
    }
}
