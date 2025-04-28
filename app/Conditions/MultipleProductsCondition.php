<?php

namespace App\Conditions;

use App\Contracts\Cart;
use App\Contracts\DiscountCondition;
use App\Contracts\User;

class MultipleProductsCondition implements DiscountCondition
{
    /**
     * @var array<int, int> Map of product IDs to minimum quantities
     */
    protected array $requiredProducts = [];

    /**
     * Create a new condition that checks if the cart contains minimum quantities of multiple products.
     *
     * @param array<int, int> $productQuantities Map of product IDs to required quantities
     */
    public function __construct(array $productQuantities)
    {
        foreach ($productQuantities as $productId => $quantity) {
            if (!is_int($productId) || !is_int($quantity) || $quantity <= 0) {
                throw new \InvalidArgumentException('Product IDs and quantities must be positive integers');
            }

            $this->requiredProducts[$productId] = $quantity;
        }
    }

    /**
     * Check if the condition is satisfied.
     *
     * @param Cart $cart
     * @param User|null $user
     * @return bool
     */
    public function isSatisfied(Cart $cart, ?User $user): bool
    {
        foreach ($this->requiredProducts as $productId => $requiredQuantity) {
            $actualQuantity = $cart->getProductQuantity($productId);

            if ($actualQuantity < $requiredQuantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all required product quantities.
     *
     * @return array<int, int>
     */
    public function getRequiredProducts(): array
    {
        return $this->requiredProducts;
    }

    /**
     * Add a product requirement to the condition.
     *
     * @param int $productId
     * @param int $quantity
     * @return self
     */
    public function addProductRequirement(int $productId, int $quantity): self
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be a positive integer');
        }

        $this->requiredProducts[$productId] = $quantity;
        return $this;
    }
}
