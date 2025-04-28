<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

/**
 * Data class for representing percentage values
 *
 * This class encapsulates percentage values with validation constraints
 * to ensure they are within the valid range (0-100). It provides helpers
 * for formatting and converting to decimal representation.
 */
class PercentageData extends Data
{
    /**
     * Create a new percentage data instance
     *
     * @param float $value The percentage value (0-100)
     */
    public function __construct(
        #[Numeric]
        #[Min(0)]
        #[Max(100)]
        public float $value
    ) {}

    /**
     * Convert the percentage to its decimal representation
     *
     * For example, 25% becomes 0.25
     *
     * @return float The decimal representation of the percentage
     */
    public function asDecimal(): float
    {
        return $this->value / 100;
    }

    /**
     * Format the percentage as a string with % symbol
     *
     * @return string The formatted percentage string (e.g., "25%")
     */
    public function __toString(): string
    {
        return "{$this->value}%";
    }
}
