<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // 'quantity_discount', etc.
            $table->enum('type', ['percentage', 'fixed']);
            $table->unsignedInteger('amount'); // percentage as float*100 or fixed in cents
            $table->json('conditions'); // json-encoded list of conditions
            $table->boolean('combinable')->default(true);
            $table->unsignedInteger('priority')->default(100);
            $table->unsignedInteger('max_discount')->nullable(); // in cents
            $table->timestamp('valid_from')->nullable(); // start date of the discount
            $table->timestamp('valid_to')->nullable(); // end date of the discount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
