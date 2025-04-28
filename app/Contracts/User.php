<?php

namespace App\Contracts;

/**
 * Interface for user entities in the discount system
 *
 * This interface defines the minimal contract for user implementations
 * that can be used with the discount system. It's intentionally minimal
 * to allow for flexibility in different user implementations.
 */
interface User
{
    /**
     * Check if the user is authenticated
     *
     * @return bool True if the user is authenticated, false otherwise
     */
    public function isAuthenticated(): bool;
}
