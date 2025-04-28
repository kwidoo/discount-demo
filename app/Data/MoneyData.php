<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

/**
 * Data class for representing monetary values
 *
 * This class encapsulates money amounts with their associated currency.
 * It uses the Spatie Data package for automatic validation and casting.
 */
class MoneyData extends Data
{
    /**
     * Create a new money data instance
     *
     * @param float $amount The monetary amount
     * @param string $currency The currency code (default: 'USD')
     */
    public function __construct(
        #[Numeric]
        #[Min(0.01)]
        public float $amount,
        #[StringType]
        public string $currency = 'USD'
    ) {}

    /**
     * Add another MoneyData instance to this one
     *
     * @param MoneyData $other The other MoneyData instance
     * @return self A new MoneyData instance with the summed amount
     * @throws \InvalidArgumentException If the currencies do not match
     */
    public function add(MoneyData $other): self
    {
        $this->ensureSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    /**
     * Subtract another MoneyData instance from this one
     *
     * @param MoneyData $other The other MoneyData instance
     * @return self A new MoneyData instance with the subtracted amount
     * @throws \InvalidArgumentException If the currencies do not match
     */
    public function subtract(MoneyData $other): self
    {
        $this->ensureSameCurrency($other);
        $result = $this->amount - $other->amount;
        return new self(max(0, $result), $this->currency);
    }

    /**
     * Multiply the monetary amount by a given multiplier
     *
     * @param float $multiplier The multiplier
     * @return self A new MoneyData instance with the multiplied amount
     */
    public function multiply(float $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    /**
     * Ensure that the currency of another MoneyData instance matches this one
     *
     * @param MoneyData $other The other MoneyData instance
     * @throws \InvalidArgumentException If the currencies do not match
     */
    protected function ensureSameCurrency(MoneyData $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException("Cannot operate on different currencies: {$this->currency} and {$other->currency}");
        }
    }

    /**
     * Format the money amount with its currency symbol
     *
     * @return string The formatted money string (e.g., "$10.00")
     */
    public function __toString(): string
    {
        $symbol = $this->getCurrencySymbol();
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get the symbol for the current currency
     *
     * @return string The currency symbol
     */
    private function getCurrencySymbol(): string
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
        ];

        return $symbols[$this->currency] ?? $this->currency . ' ';
    }
}
