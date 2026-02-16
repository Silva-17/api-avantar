<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quote_statuses', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        // Inserir os status iniciais
        DB::table('quote_statuses')->insert([
            ['id' => 0, 'name' => 'Em Fila', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1, 'name' => 'Processando', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Concluido', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Negado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Cancelado', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_statuses');
    }
};
