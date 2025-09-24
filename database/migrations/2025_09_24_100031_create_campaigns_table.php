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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['hajj', 'omra']);
            $table->integer('year_hijri');
            $table->integer('year_gregorian');
            $table->decimal('price', 10, 2);
            $table->integer('quota')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->timestamps();

            $table->index(['type', 'year_hijri']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
