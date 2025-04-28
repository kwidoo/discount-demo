<?php

namespace App\Conditions;

use App\Contracts\DiscountCondition;
use App\Contracts\Cart;
use App\Contracts\User;

class DiscountCodeCondition implements DiscountCondition
{
    /**
     * Create a new condition that checks if the provided code matches the expected code.
     *
     * @param string $expectedCode The code that will activate the discount
     * @param string $providedCode The code provided by the user
     */
    public function __construct(
        protected string $expectedCode,
        protected string $providedCode
    ) {}

    /**
     * Check if the condition is satisfied (codes match).
     *
     * @param Cart $cart
     * @param User|null $user
     * @return bool
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        return $this->providedCode === $this->expectedCode;
    }

    /**
     * Get the expected code.
     *
     * @return string
     */
    public function getExpectedCode(): string
    {
        return $this->expectedCode;
    }

    /**
     * Get the provided code.
     *
     * @return string
     */
    public function getProvidedCode(): string
    {
        return $this->providedCode;
    }
}
