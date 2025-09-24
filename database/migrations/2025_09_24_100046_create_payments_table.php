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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilgrim_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'check']);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_number', 100)->unique()->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('pilgrim_id');
            $table->index('payment_date');
            $table->index('payment_method');
            $table->index('receipt_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
