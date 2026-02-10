<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_residentials', function (Blueprint $table) {
            $table->id();

            // 1. Dados Iniciais
            $table->string('razao_social');
            $table->string('cnpj'); // CPF ou CNPJ
            $table->string('email');
            $table->string('telefone');
            $table->string('cep');
            $table->string('seguro_novo');
            $table->string('seguradora_anterior')->nullable();

            // 2. Especificação Residencial
            $table->text('endereco_completo');
            $table->string('segmento');
            $table->string('tipo_construcao');
            $table->string('tipo_logradouro');
            $table->string('tipo_residencia');

            // 3. Avaliação de Risco
            $table->boolean('alarme_roubo')->default(false);
            $table->boolean('predio_conteudo')->default(false);
            $table->boolean('grades_janela')->default(false);
            $table->boolean('proprietario_imovel')->default(false);
            $table->boolean('zona_rural')->default(false);
            $table->string('condominio_fechado');
            $table->decimal('valor_novo', 15, 2);
            $table->decimal('valor_imovel', 15, 2);
            $table->text('clausula_beneficiaria')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_residentials');
    }
};
