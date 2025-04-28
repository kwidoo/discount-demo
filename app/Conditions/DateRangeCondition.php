<?php

namespace App\Conditions;

use App\Contracts\Cart;
use App\Contracts\DiscountCondition;
use App\Contracts\Product;
use App\Contracts\User;
use Carbon\Carbon;

class DateRangeCondition implements DiscountCondition
{
    private ?Carbon $validFrom;
    private ?Carbon $validTo;

    public function __construct(?string $validFrom = null, ?string $validTo = null)
    {
        $this->validFrom = $validFrom ? Carbon::parse($validFrom) : null;
        $this->validTo = $validTo ? Carbon::parse($validTo) : null;
    }

    public function isSatisfiedBy(Cart $cart, ?Product $product = null, ?User $user = null): bool
    {
        // If both dates are null, the condition is not applicable, so return true
        if ($this->validFrom === null && $this->validTo === null) {
            return true;
        }

        $now = Carbon::now();

        // Check if current date is after validFrom (or if validFrom is null)
        $afterStart = $this->validFrom === null || $now->greaterThanOrEqualTo($this->validFrom);

        // Check if current date is before validTo (or if validTo is null)
        $beforeEnd = $this->validTo === null || $now->lessThanOrEqualTo($this->validTo);

        return $afterStart && $beforeEnd;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['valid_from'] ?? null,
            $data['valid_to'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'type' => 'date_range',
            'valid_from' => $this->validFrom ? $this->validFrom->toIso8601String() : null,
            'valid_to' => $this->validTo ? $this->validTo->toIso8601String() : null,
        ];
    }
}
