<?php

namespace App\Services;

use App\Contracts\Discount;
use App\Contracts\DiscountStrategy;
use App\Contracts\Cart;
use App\Contracts\DiscountService;
use App\Contracts\User;
use App\Contracts\DiscountFactory;
use App\Strategies\OrderedDiscountStrategy;

/**
 * Service responsible for managing and applying discounts
 *
 * This class acts as the main coordinator for the discount system,
 * managing registered discounts and delegating to the selected
 * strategy for applying them to carts.
 */
class DefaultDiscountService implements DiscountService
{
    /**
     * Create a new discount service
     *
     * @param DiscountStrategy|null $strategy The strategy to use (defaults to OrderedDiscountStrategy)
     */
    public function __construct(
        protected ?DiscountStrategy $strategy,
        protected ?DiscountFactory $factory = null,
        protected array $registeredDiscounts = []
    ) {}

    /**
     * Register a discount with the service
     *
     * @param Discount $discount The discount to register
     * @return self For method chaining
     */
    public function registerDiscount(Discount $discount): self
    {
        $this->registeredDiscounts[$discount->getCode()] = $discount;
        return $this;
    }

    /**
     * Register multiple discounts at once
     *
     * @param Discount[] $discounts Array of discounts to register
     * @return self For method chaining
     */
    public function registerDiscounts(array $discounts): self
    {
        foreach ($discounts as $discount) {
            $this->registerDiscount($discount);
        }
        return $this;
    }

    /**
     * Remove a discount from the registry by code
     *
     * @param string $discountCode The code of the discount to remove
     * @return self For method chaining
     */
    public function removeDiscount(string $discountCode): self
    {
        unset($this->registeredDiscounts[$discountCode]);
        return $this;
    }


    /**
     * Calculate the total discount for a cart
     *
     * @param Cart $cart The cart to calculate discounts for
     * @param User|null $user The user, if any
     * @return float The total discount amount
     */
    public function calculateDiscount(Cart $cart, ?User $user = null): float
    {
        // Update each discount with the provided code
        $discounts = array_values($this->registeredDiscounts);

        return $this->strategy->applyDiscounts(
            $discounts,
            $cart,
            $user
        );
    }

    /**
     * Get detailed information about applied discounts
     *
     * Only available when using OrderedDiscountStrategy
     *
     * @return array Information about applied discounts
     */
    public function getAppliedDiscounts(): array
    {
        if ($this->strategy instanceof OrderedDiscountStrategy) {
            return $this->strategy->getAppliedDiscounts();
        }

        return [];
    }

    /**
     * Get all registered discounts
     *
     * @return Discount[] Array of all registered discounts
     */
    public function getAllDiscounts(): array
    {
        return $this->registeredDiscounts;
    }
}
