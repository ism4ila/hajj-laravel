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
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('transferred_to_bank')->default(false)->after('status');
            $table->date('bank_transfer_date')->nullable()->after('transferred_to_bank');
            $table->string('bank_transfer_reference')->nullable()->after('bank_transfer_date');
            $table->text('bank_transfer_notes')->nullable()->after('bank_transfer_reference');
            $table->unsignedBigInteger('transferred_by')->nullable()->after('bank_transfer_notes');

            $table->foreign('transferred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['transferred_by']);
            $table->dropColumn([
                'transferred_to_bank',
                'bank_transfer_date',
                'bank_transfer_reference',
                'bank_transfer_notes',
                'transferred_by'
            ]);
        });
    }
};
