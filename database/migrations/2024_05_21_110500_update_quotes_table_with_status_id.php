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
        Schema::table('quotes', function (Blueprint $table) {
            // Adicionar a nova coluna de status
            $table->integer('quote_status_id')->default(0)->after('user_id');

            // Adicionar a chave estrangeira
            $table->foreign('quote_status_id')->references('id')->on('quote_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['quote_status_id']);
            $table->dropColumn('quote_status_id');
        });
    }
};
