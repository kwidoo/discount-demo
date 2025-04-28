<?php

namespace App\Contracts;

/**
 * Interface for discount calculation strategies
 *
 * This interface defines the contract for classes that calculate
 * discount amounts based on different rules (fixed amount, percentage, etc.).
 * Following the Strategy pattern, calculators encapsulate different algorithms
 * for determining discount amounts.
 */
interface DiscountCalculator
{
    /**
     * Calculate a discount amount for the given cart
     *
     * @param Cart $cart The cart to calculate the discount for
     * @return float The calculated discount amount
     */
    public function calculate(Cart $cart): float;
}
