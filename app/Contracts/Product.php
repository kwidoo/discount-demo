<?php

namespace App\Contracts;

/**
 * Interface for product entities in the discount system
 *
 * This interface defines the minimal contract for product implementations
 * that can be used with the discount system. It's intentionally minimal
 * to allow for flexibility in different product implementations.
 *
 * Products can be referenced by their ID in discount conditions.
 */
interface Product {}
