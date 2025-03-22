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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->decimal('buy_price', 10,2);
            $table->decimal('net_price', 10,2);
            $table->enum('discount_type',['percentage','amount']);
            $table->decimal('discount', 10 ,2)->nullable();
            $table->decimal('price', 10,2);
            $table->integer('quantity');
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
//            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
