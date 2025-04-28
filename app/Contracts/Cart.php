<?php

namespace App\Contracts;

/**
 * Interface for shopping cart functionality
 *
 * This interface defines the contract for shopping cart implementations,
 * providing methods to manage items, calculate totals, and retrieve
 * product information needed for discount calculations.
 */
interface Cart
{
    /**
     * Get the quantity of a specific product in the cart
     *
     * @param string $product The product ID to check
     * @return int The quantity of the product in the cart
     */
    public function getProductQuantity(string $product): int;

    /**
     * Get all category IDs of products in the cart
     *
     * @return array Array of category IDs
     */
    public function getProductCategoryIds(): array;

    /**
     * Add an item to the cart
     *
     * @param string $item The item/product ID
     * @param float $price The price of the item
     * @return void
     */
    public function addItem(string $item, float $price): void;

    /**
     * Remove an item from the cart
     *
     * @param string $item The item/product ID to remove
     * @return void
     */
    public function removeItem(string $item): void;

    /**
     * Calculate the total price of all items in the cart
     *
     * @return float The total price
     */
    public function total(): float;

    /**
     * Get all items in the cart
     *
     * @return array Array of cart items
     */
    public function items(): array;

    /**
     * Clear all items from the cart
     *
     * @return void
     */
    public function clear(): void;
}
