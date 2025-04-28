<?php

namespace App\Discount;

use App\Calculators\FixedAmountCalculator;
use App\Calculators\PercentageCalculator;
use App\Conditions\DateRangeCondition;
use App\Conditions\DiscountCodeCondition;
use App\Contracts\Discount;
use App\Contracts\DiscountCondition;
use App\Contracts\DiscountFactory;

/**
 * Factory for creating different types of discounts
 *
 * Simplifies the creation of common discount types by encapsulating
 * the complexity of assembling calculator and condition components.
 * This class follows the Factory pattern from SOLID principles.
 */
class DefaultDiscountFactory implements DiscountFactory
{

    /**
     * Create a fixed amount discount
     *
     * @param string $code Unique discount code
     * @param float $amount Fixed discount amount
     * @param DiscountCondition[] $conditions Array of conditions that must be satisfied
     * @param bool $combinable Whether this discount can be combined with others
     * @param float|null $maxAmount Maximum discount amount (optional)
     * @param int $priority Priority of the discount (lower values = higher priority)
     * @param string|null $providedCode The discount code provided by the user (optional)
     * @param string|null $validFrom Start date of the discount (optional)
     * @param string|null $validTo End date of the discount (optional)
     * @return Discount
     */
    public function createFixedDiscount(
        string $code,
        float $amount,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null,
        ?string $validFrom = null,
        ?string $validTo = null
    ): Discount {
        // Add discount code condition if code is provided
        if ($code) {
            $conditions[] = new DiscountCodeCondition($code, $providedCode ?? '');
        }

        // Add date range condition if either date is provided
        if ($validFrom !== null || $validTo !== null) {
            $conditions[] = new DateRangeCondition($validFrom, $validTo);
        }

        return new CompositeDiscount(
            new FixedAmountCalculator($amount),
            $conditions,
            $combinable,
            $maxAmount,
            $priority
        );
    }

    /**
     * Create a percentage-based discount
     *
     * @param string $code Unique discount code
     * @param float $percentage Discount percentage (0-100)
     * @param DiscountCondition[] $conditions Array of conditions that must be satisfied
     * @param bool $combinable Whether this discount can be combined with others
     * @param float|null $maxAmount Maximum discount amount (optional)
     * @param int $priority Priority of the discount (lower values = higher priority)
     * @param string|null $providedCode The discount code provided by the user (optional)
     * @param string|null $validFrom Start date of the discount (optional)
     * @param string|null $validTo End date of the discount (optional)
     * @return Discount
     */
    public function createPercentageDiscount(
        string $code,
        float $percentage,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null,
        ?string $validFrom = null,
        ?string $validTo = null
    ): Discount {
        // Add discount code condition if code is provided
        if ($code) {
            $conditions[] = new DiscountCodeCondition($code, $providedCode ?? '');
        }

        // Add date range condition if either date is provided
        if ($validFrom !== null || $validTo !== null) {
            $conditions[] = new DateRangeCondition($validFrom, $validTo);
        }

        return new CompositeDiscount(
            new PercentageCalculator($percentage),
            $conditions,
            $combinable,
            $maxAmount,
            $priority
        );
    }

    /**
     * Create a custom discount with the specified calculator
     *
     * @param string $code Unique discount code
     * @param \App\Contracts\DiscountCalculator $calculator The calculator to use
     * @param DiscountCondition[] $conditions Array of conditions that must be satisfied
     * @param bool $combinable Whether this discount can be combined with others
     * @param float|null $maxAmount Maximum discount amount (optional)
     * @param int $priority Priority of the discount (lower values = higher priority)
     * @param string|null $providedCode The discount code provided by the user (optional)
     * @param string|null $validFrom Start date of the discount (optional)
     * @param string|null $validTo End date of the discount (optional)
     * @return Discount
     */
    public function createCustomDiscount(
        string $code,
        \App\Contracts\DiscountCalculator $calculator,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null,
        ?string $validFrom = null,
        ?string $validTo = null
    ): Discount {
        // Add discount code condition if code is provided
        if ($code) {
            $conditions[] = new DiscountCodeCondition($code, $providedCode ?? '');
        }

        // Add date range condition if either date is provided
        if ($validFrom !== null || $validTo !== null) {
            $conditions[] = new DateRangeCondition($validFrom, $validTo);
        }

        return new CompositeDiscount(
            $calculator,
            $conditions,
            $combinable,
            $maxAmount,
            $priority
        );
    }
}
