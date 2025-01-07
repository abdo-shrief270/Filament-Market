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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->enum('type',['user','admin','manager','courier'])->default('user');
            $table->string('id_number')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('password');
            $table->boolean('active')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
        \App\Models\User::create([
            'name'=>'Abdo Shrief',
            'phone'=>'01270989676',
            'email'=>'abdo.shrief270@gmail.com',
            'type'=>'admin',
            'password'=>\Illuminate\Support\Facades\Hash::make('12345678'),
            'active'=>true
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
