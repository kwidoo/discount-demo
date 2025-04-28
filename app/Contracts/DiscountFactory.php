<?php

namespace App\Contracts;

interface DiscountFactory
{
    public function createFixedDiscount(
        string $code,
        float $amount,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null
    ): Discount;

    public function createPercentageDiscount(
        string $code,
        float $percentage,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null
    ): Discount;

    public function createCustomDiscount(
        string $code,
        \App\Contracts\DiscountCalculator $calculator,
        array $conditions = [],
        bool $combinable = false,
        ?float $maxAmount = null,
        int $priority = 100,
        ?string $providedCode = null
    ): Discount;
}
