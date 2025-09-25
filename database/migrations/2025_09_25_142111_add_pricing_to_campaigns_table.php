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
        Schema::table('campaigns', function (Blueprint $table) {
            // Tarifs par catégorie
            $table->decimal('price_classic', 10, 2)->default(0)->after('description');
            $table->decimal('price_vip', 10, 2)->default(0)->after('price_classic');

            // Modification du statut pour inclure fermé/ouvert
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'open', 'closed', 'active', 'completed', 'cancelled'])->default('draft')->after('price_vip');

            // Informations supplémentaires
            $table->text('classic_description')->nullable()->after('status');
            $table->text('vip_description')->nullable()->after('classic_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['price_classic', 'price_vip', 'classic_description', 'vip_description']);
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
        });
    }
};
