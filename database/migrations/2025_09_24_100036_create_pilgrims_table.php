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
        Schema::create('pilgrims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->enum('status', ['registered', 'documents_pending', 'documents_complete', 'paid', 'cancelled'])->default('registered');
            $table->timestamps();

            $table->index('campaign_id');
            $table->index('status');
            $table->index(['lastname', 'firstname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilgrims');
    }
};
