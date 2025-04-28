<?php

namespace App\Resolvers;

use App\Contracts\DiscountCondition;
use App\Conditions\ByProductIdCondition;
use App\Conditions\AuthenticatedUserCondition;
use App\Conditions\CartTotalCondition;
use App\Conditions\DateRangeCondition;

class ConditionResolver
{
    public function resolve(array $condition): DiscountCondition
    {
        return match ($condition['type']) {
            'by_product_id' => new ByProductIdCondition($condition['product_id'], $condition['min_quantity']),
            'authenticated' => new AuthenticatedUserCondition(),
            'cart_total' => new CartTotalCondition($condition['min_total']),
            'date_range' => DateRangeCondition::fromArray($condition),

            default => throw new \RuntimeException("Unknown condition type: {$condition['type']}"),
        };
    }
}
