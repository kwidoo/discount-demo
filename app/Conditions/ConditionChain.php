<?php

namespace App\Conditions;

use App\Contracts\Cart;
use App\Contracts\DiscountCondition;
use App\Contracts\User;

/**
 * A composite condition that chains multiple conditions together
 *
 * This class implements the Composite pattern to allow grouping multiple
 * discount conditions where all conditions must be satisfied for the
 * discount to apply.
 */
class ConditionChain implements DiscountCondition
{
    /**
     * Array of conditions in this chain
     *
     * @var DiscountCondition[]
     */
    protected array $conditions = [];

    /**
     * Create a new condition chain
     *
     * @param DiscountCondition[] $conditions Array of conditions to include in the chain
     * @throws \InvalidArgumentException When a non-DiscountCondition object is passed
     */
    public function __construct(array $conditions = [])
    {
        foreach ($conditions as $condition) {
            if (!$condition instanceof DiscountCondition) {
                throw new \InvalidArgumentException('All conditions must implement DiscountCondition');
            }
            $this->conditions[] = $condition;
        }
    }

    /**
     * Add a condition to the chain
     *
     * @param DiscountCondition $condition The condition to add
     * @return self For method chaining
     */
    public function addCondition(DiscountCondition $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * Check if all conditions in the chain are satisfied
     *
     * All conditions must be satisfied for this composite condition
     * to be considered satisfied (AND logic).
     *
     * @param Cart $cart The cart to check
     * @param User|null $user The user to check
     * @return bool True only if all conditions are satisfied
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->isSatisfied($cart, $user)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all conditions in this chain
     *
     * @return DiscountCondition[] Array of conditions
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
