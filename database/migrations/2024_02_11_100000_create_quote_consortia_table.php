<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_consortia', function (Blueprint $table) {
            $table->id();

            // 1. Identificação do Interessado
            $table->string('nome_completo');
            $table->string('cpf_cnpj');
            $table->string('email');
            $table->string('telefone');
            $table->string('cep');

            // 2. Questionário de Consórcio
            $table->string('tipo_pessoa'); // Física, Jurídica
            $table->boolean('cotar_seguro_vida')->default(false);
            $table->decimal('valor_parcela', 15, 2);
            $table->decimal('valor_carta_credito', 15, 2);
            $table->string('tipo_produto'); // Imóvel, Veículo
            $table->string('tipo_grupo'); // Em andamento, Novo

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_consortia');
    }
};
