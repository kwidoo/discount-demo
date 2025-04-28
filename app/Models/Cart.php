<?php

namespace App\Models;

use App\Contracts\Cart as CartContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model implements CartContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the user that owns this cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products in this cart.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Calculate the total price of all items in the cart
     *
     * @return float The total price
     */
    public function total(): float
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->price * $product->pivot->quantity;
        }
        return $total;
    }

    /**
     * Get the quantity of a specific product in the cart
     *
     * @param string $product The product ID to check
     * @return int The quantity of the product in the cart
     */
    public function getProductQuantity(string $product): int
    {
        $item = $this->products()->where('products.id', $product)->first();
        return $item ? $item->pivot->quantity : 0;
    }

    /**
     * Get all category IDs of products in the cart
     *
     * @return array Array of category IDs
     */
    public function getProductCategoryIds(): array
    {
        return $this->products()
            ->whereNotNull('category_id')
            ->pluck('category_id')
            ->unique()
            ->toArray();
    }

    /**
     * Add an item to the cart
     *
     * @param string $item The item/product ID
     * @param float $price The price of the item (not used in DB implementation)
     * @return void
     */
    public function addItem(string $item, float $price): void
    {
        $existingProduct = $this->products()->where('products.id', $item)->first();

        if ($existingProduct) {
            // Update quantity
            $this->products()->updateExistingPivot($item, [
                'quantity' => $existingProduct->pivot->quantity + 1
            ]);
        } else {
            // Add new product to cart
            $this->products()->attach($item, ['quantity' => 1]);
        }
    }

    /**
     * Remove an item from the cart
     *
     * @param string $item The item/product ID to remove
     * @return void
     */
    public function removeItem(string $item): void
    {
        $existingProduct = $this->products()->where('products.id', $item)->first();

        if ($existingProduct) {
            if ($existingProduct->pivot->quantity > 1) {
                // Decrease quantity
                $this->products()->updateExistingPivot($item, [
                    'quantity' => $existingProduct->pivot->quantity - 1
                ]);
            } else {
                // Remove entirely
                $this->products()->detach($item);
            }
        }
    }

    /**
     * Get all items in the cart
     *
     * @return array Array of cart items
     */
    public function items(): array
    {
        $items = [];
        foreach ($this->products as $product) {
            $items[] = [
                'product_id' => (string) $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $product->pivot->quantity,
                'category_id' => $product->category_id,
            ];
        }
        return $items;
    }

    /**
     * Clear all items from the cart
     *
     * @return void
     */
    public function clear(): void
    {
        $this->products()->detach();
    }
}
