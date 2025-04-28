<?php

namespace App\Contracts;

interface DiscountService
{
    public function registerDiscount(Discount $discount): self;
    public function calculateDiscount(Cart $cart, ?User $user = null): float;
}
