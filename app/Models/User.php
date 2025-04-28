<?php

namespace App\Models;

use App\Contracts\User as UserContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model representing application users
 *
 * This class extends Laravel's Authenticatable class and implements
 * the User contract for consistency with the discount system.
 */
class User extends Authenticatable implements UserContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Flag indicating if user is authenticated
     *
     * @var bool
     */
    public bool $is_authenticated = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_authenticated' => 'boolean',
        ];
    }

    /**
     * Get the user's cart.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get or create a cart for the user.
     *
     * @return Cart
     */
    public function getCart(): Cart
    {
        if (!$this->cart) {
            $this->cart()->create();
            $this->refresh();
        }

        return $this->cart;
    }

    /**
     * Get the user's cart items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function isAuthenticated(): bool
    {
        return $this->is_authenticated;
    }
}
