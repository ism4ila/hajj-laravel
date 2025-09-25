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
        Schema::table('pilgrims', function (Blueprint $table) {
            // Catégorie du pèlerin (classic ou vip)
            $table->enum('category', ['classic', 'vip'])->default('classic')->after('campaign_id');

            // Supprimer les colonnes total_amount, paid_amount, remaining_amount
            // car elles seront calculées dynamiquement selon la catégorie
            $table->dropColumn(['total_amount', 'paid_amount', 'remaining_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilgrims', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->default(0);
        });
    }
};
