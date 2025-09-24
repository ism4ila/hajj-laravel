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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilgrim_id')->constrained()->onDelete('cascade');
            $table->string('cni')->nullable();
            $table->string('cni_file', 500)->nullable();
            $table->string('passport')->nullable();
            $table->string('passport_file', 500)->nullable();
            $table->string('visa')->nullable();
            $table->string('visa_file', 500)->nullable();
            $table->string('vaccination_certificate')->nullable();
            $table->string('vaccination_file', 500)->nullable();
            $table->string('photo_file', 500)->nullable();
            $table->boolean('documents_complete')->default(false);
            $table->timestamps();

            $table->index('pilgrim_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
