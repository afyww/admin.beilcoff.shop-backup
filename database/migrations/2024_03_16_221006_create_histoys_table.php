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
        Schema::create('histoys', function (Blueprint $table) {
            $table->id();
            $table->string('kursi');
            $table->string('name');
            $table->string('no_order')->nullable(); 
            $table->string('order');
            $table->string('payment_type');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->string('status')->nullable(); 
            $table->foreignId('settlement_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histoys');
    }
};
