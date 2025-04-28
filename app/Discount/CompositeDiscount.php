<?php

namespace App\Discount;

use App\Contracts\Discount;
use App\Contracts\DiscountCalculator;
use App\Contracts\Cart;
use App\Contracts\User;

/**
 * Composite implementation of the Discount contract
 *
 * Combines a calculator with multiple conditions to create a complete
 * discount that can be applied to a cart. This class follows the
 * Composite pattern by aggregating multiple conditions.
 *
 * @property \App\Contracts\DiscountCondition[] $conditions
 */
class CompositeDiscount implements Discount
{
    /**
     * The discount code or identifier
     *
     * @var string|null
     */
    protected ?string $code = null;

    /**
     * Create a new composite discount
     *
     * @param DiscountCalculator $calculator The calculator that determines the discount amount
     * @param array $conditions Array of conditions that must be satisfied
     * @param bool $combinable Whether this discount can be combined with others
     * @param float|null $maxAmount Maximum allowed discount amount
     * @param int $priority Priority value (lower values = higher priority)
     */
    public function __construct(
        protected DiscountCalculator $calculator,
        protected array $conditions = [],
        protected bool $combinable = false,
        protected ?float $maxAmount = null,
        protected int $priority = 100, // Default priority (lower values = higher priority)
    ) {}

    /**
     * Get the discount code/identifier
     *
     * If a DiscountCodeCondition exists, its code will be used.
     * Otherwise, generates a unique identifier.
     *
     * @return string The discount code
     */
    public function getCode(): string
    {
        // Extract code from DiscountCodeCondition if exists
        foreach ($this->conditions as $condition) {
            if ($condition instanceof \App\Conditions\DiscountCodeCondition) {
                return $condition->getExpectedCode();
            }
        }

        // Fallback to a generated ID if no code condition exists
        if ($this->code === null) {
            $this->code = 'discount_' . substr(md5(serialize($this)), 0, 8);
        }

        return $this->code;
    }

    /**
     * Get all the conditions for this discount
     *
     * @return \App\Contracts\DiscountCondition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Check if this discount is applicable to the cart
     *
     * @param Cart $cart The cart to check against
     * @param User|null $user The user, if any
     * @return bool True if all conditions are satisfied
     */
    public function isApplicable(Cart $cart, ?User $user): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->isSatisfied($cart, $user)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Calculate the discount amount for the cart
     *
     * @param Cart $cart The cart to calculate discount for
     * @param User|null $user The user, if any
     * @return float The discount amount, limited by maxAmount if set
     */
    public function calculate(Cart $cart, ?User $user): float
    {
        $amount = $this->calculator->calculate($cart);
        return $this->maxAmount ? min($amount, $this->maxAmount) : $amount;
    }

    /**
     * Check if this discount can be combined with others
     *
     * @return bool True if combinable, false otherwise
     */
    public function isCombinable(): bool
    {
        return $this->combinable;
    }

    /**
     * Get the priority of this discount
     *
     * @return int The priority value (lower values = higher priority)
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
