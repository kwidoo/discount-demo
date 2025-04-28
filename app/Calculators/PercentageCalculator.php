<?php

namespace App\Calculators;

use App\Contracts\DiscountCalculator;
use App\Contracts\Cart;
use App\Data\PercentageData;

/**
 * Calculator that applies a percentage-based discount
 *
 * This class implements the DiscountCalculator interface to provide
 * percentage-based discounts (e.g., 10% off) calculated from the cart total.
 */
class PercentageCalculator implements DiscountCalculator
{
    /**
     * The percentage value of the discount
     *
     * @var PercentageData
     */
    private PercentageData $percentage;

    /**
     * Create a new percentage calculator
     *
     * @param float $percent The percentage discount (0-100)
     */
    public function __construct(float $percent)
    {
        $this->percentage = new PercentageData($percent);
    }

    /**
     * Calculate the discount amount for a cart
     *
     * Computes the discount as a percentage of the cart total.
     *
     * @param Cart $cart The cart to calculate the discount for
     * @return float The calculated discount amount
     */
    public function calculate(Cart $cart): float
    {
        return $cart->total() * $this->percentage->asDecimal();
    }
}
