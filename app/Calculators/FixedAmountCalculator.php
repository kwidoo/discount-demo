<?php

namespace App\Calculators;

use App\Contracts\DiscountCalculator;
use App\Data\MoneyData;
use App\Contracts\Cart;

/**
 * Calculator that applies a fixed monetary amount as a discount
 *
 * This class implements the DiscountCalculator interface to provide
 * fixed-amount discounts (e.g., $10 off) regardless of cart contents.
 */
class FixedAmountCalculator implements DiscountCalculator
{
    /**
     * The monetary value of the discount
     *
     * @var MoneyData
     */
    protected MoneyData $money;

    /**
     * Create a new fixed amount calculator
     *
     * @param float $amount The fixed discount amount
     * @param string $currency The currency code (default: 'USD')
     */
    public function __construct(float $amount, string $currency = 'USD')
    {
        $this->money = new MoneyData($amount, $currency);
    }

    /**
     * Calculate the discount amount for a cart
     *
     * For fixed discounts, this simply returns the predefined amount
     * regardless of the cart contents.
     *
     * @param Cart $cart The cart to calculate the discount for
     * @return float The calculated discount amount
     */
    public function calculate(Cart $cart): float
    {
        return $this->money->amount;
    }
}
