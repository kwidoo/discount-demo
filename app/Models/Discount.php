<?php

namespace App\Models;

use App\Conditions\DateRangeCondition;
use App\Contracts\Cart;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type',
        'amount',
        'conditions',
        'combinable',
        'priority',
        'max_discount',
        'valid_from',
        'valid_to'
    ];

    protected $casts = [
        'conditions' => 'json',
        'amount' => 'integer',
        'combinable' => 'boolean',
        'priority' => 'integer',
        'max_discount' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    /**
     * Get the date range condition for this discount
     *
     * @return DateRangeCondition
     */
    public function getDateRangeCondition(): DateRangeCondition
    {
        return new DateRangeCondition(
            $this->valid_from ? $this->valid_from->toDateTimeString() : null,
            $this->valid_to ? $this->valid_to->toDateTimeString() : null
        );
    }

    /**
     * Check if the discount is currently valid based on date ranges
     *
     * @return bool
     */
    public function isCurrentlyValid(): bool
    {
        return $this->getDateRangeCondition()->isSatisfiedBy(app(Cart::class));
    }
}
