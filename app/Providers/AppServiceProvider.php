<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\DiscountFactory;
use App\Conditions\AuthenticatedUserCondition;
use App\Conditions\ByProductIdCondition;
use App\Contracts\DiscountService;
use App\Contracts\DiscountStrategy;
use App\Contracts\User;
use App\Discount\DefaultDiscountFactory;
use App\Models\User as ModelsUser;
use App\Services\DefaultDiscountService;
use App\Strategies\OrderedDiscountStrategy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register DiscountService as a singleton
        $this->app->singleton(DiscountService::class, function ($app) {
            return new DefaultDiscountService(
                $app->make(DiscountStrategy::class),
                $app->make(DiscountFactory::class)
            );
        });

        $this->app->bind(DiscountStrategy::class, function ($app) {
            return new OrderedDiscountStrategy();
        });

        // Register DiscountFactory as a singleton
        $this->app->bind(DiscountFactory::class, function ($app) {
            return new DefaultDiscountFactory();
        });

        $this->app->bind(User::class, ModelsUser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Get the DiscountService instance
        $discountService = app(DiscountService::class);
        $discountFactory = app(DiscountFactory::class);

        // 1. Register quantity discount: 10% off when buying 3+ of product ID 2
        $quantityDiscount = $discountFactory->createPercentageDiscount(
            'quantity_discount',
            10.0, // 10% off
            [new ByProductIdCondition(2, 3)], // Product ID 2, minimum 3 items
            true, // Combinable
            null, // No max discount
            100 // Priority
        );
        $discountService->registerDiscount($quantityDiscount);

        // 2. Register price discount: $2 off per item for product ID 3
        $priceDiscount = $discountFactory->createFixedDiscount(
            'price_discount',
            200, // $2.00 (in cents)
            [new ByProductIdCondition(3, 1)], // Product ID 3, minimum 1 item
            true, // Combinable
            null, // No max discount
            110 // Priority
        );
        $discountService->registerDiscount($priceDiscount);

        // 3. Register authentication discount: 20% off for product ID 4 when logged in
        $authDiscount = $discountFactory->createPercentageDiscount(
            'auth_discount',
            20.0, // 20% off
            [
                new ByProductIdCondition(4, 1), // Product ID 4, minimum 1 item
                new AuthenticatedUserCondition() // User must be authenticated
            ],
            true, // Combinable
            null, // No max discount
            120 // Priority
        );
        $discountService->registerDiscount($authDiscount);
    }
}
