<?php

namespace App\Contracts;

/**
 * Interface for discount applicability conditions
 *
 * This interface defines the contract for classes that determine whether
 * a discount can be applied based on specific business rules.
 * Following the Strategy pattern, conditions encapsulate different
 * criteria for discount eligibility.
 */
interface DiscountCondition
{
    /**
     * Check if the condition is satisfied for the given cart and user
     *
     * @param Cart $cart The cart to check conditions against
     * @param User|null $user The user to check conditions against
     * @return bool True if the condition is satisfied
     */
    public function isSatisfied(Cart $cart, ?User $user): bool;
}
