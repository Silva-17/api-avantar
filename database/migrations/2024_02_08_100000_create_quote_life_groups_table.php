<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_life_groups', function (Blueprint $table) {
            $table->id();

            // 1. Questionário Corporativo
            $table->string('razao_social');
            $table->string('cnpj');
            $table->integer('qtd_funcionarios');
            $table->text('endereco');
            $table->string('ramo_atividade');

            // 2. Capitais Segurados
            $table->string('capital_segurado');

            // 3. Histórico de Seguro
            $table->boolean('seguro_vigente')->default(false);
            $table->integer('vidas_cobertas_atualmente')->nullable();
            $table->boolean('sinistro_12_meses')->default(false);

            // 4. Coberturas Desejadas
            $table->boolean('morte_basica')->default(false);
            $table->boolean('morte_acidental')->default(false);
            $table->boolean('ipa')->default(false);
            $table->boolean('ifpd')->default(false);
            $table->boolean('funeral')->default(false);
            $table->boolean('conjuge_filhos')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_life_groups');
    }
};
