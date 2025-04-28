<?php

namespace App\Conditions;

use App\Contracts\Cart;
use App\Contracts\DiscountCondition;
use App\Contracts\User;

class ProductCategoryCondition implements DiscountCondition
{
    /**
     * Create a new condition that checks if the cart contains products from specific categories.
     *
     * @param array<int> $categoryIds The categories to check for
     * @param bool $requireAll Whether all categories must be present (true) or at least one (false)
     */
    public function __construct(
        protected array $categoryIds,
        protected bool $requireAll = false
    ) {
        if (empty($categoryIds)) {
            throw new \InvalidArgumentException('At least one category ID must be provided');
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
        $cartCategoryIds = $cart->getProductCategoryIds(1);

        if ($this->requireAll) {
            // All required categories must be present in the cart
            foreach ($this->categoryIds as $categoryId) {
                if (!in_array($categoryId, $cartCategoryIds)) {
                    return false;
                }
            }
            return true;
        } else {
            // At least one of the categories must be present
            foreach ($this->categoryIds as $categoryId) {
                if (in_array($categoryId, $cartCategoryIds)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Get the category IDs this condition checks for.
     *
     * @return array<int>
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * Check if all categories are required.
     *
     * @return bool
     */
    public function requiresAll(): bool
    {
        return $this->requireAll;
    }
}
