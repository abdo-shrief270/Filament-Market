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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->bigInteger('last_customer_id')->nullable();
            $table->decimal('total_price')->default(0);
            $table->enum('discount_type',['percentage','amount']);
            $table->decimal('discount')->nullable();
            $table->foreignId('courier_id')->nullable()->constrained('users','id')->nullOnDelete();
            $table->bigInteger('last_courier_id')->nullable();
            $table->enum('order_status',['new','processing','shipped','delivered','cancelled']);
            $table->text('notes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
