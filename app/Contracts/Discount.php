<?php

namespace App\Contracts;

/**
 * Core interface for all discount types
 *
 * This interface defines the contract that all discount implementations must follow.
 * It includes methods for checking applicability, calculating discount amount,
 * determining if a discount can be combined with others, and retrieving priority.
 */
interface Discount
{
    /**
     * Get the unique code/identifier for this discount
     *
     * @return string The discount code
     */
    public function getCode(): string;

    /**
     * Get the priority value for this discount
     *
     * Lower numbers indicate higher priority when applying multiple discounts.
     *
     * @return int The priority value (lower = higher priority)
     */
    public function getPriority(): int;

    /**
     * Check if this discount is applicable to the given cart
     *
     * @param Cart $cart The cart to check against
     * @param User|null $user The user, if any
     * @return bool True if the discount can be applied
     */
    public function isApplicable(Cart $cart, ?User $user): bool;

    /**
     * Calculate the discount amount for the given cart
     *
     * @param Cart $cart The cart to calculate discount for
     * @param User|null $user The user, if any
     * @return float The calculated discount amount
     */
    public function calculate(Cart $cart, ?User $user): float;

    /**
     * Check if this discount can be combined with others
     *
     * @return bool True if combinable, false otherwise
     */
    public function isCombinable(): bool;
}
