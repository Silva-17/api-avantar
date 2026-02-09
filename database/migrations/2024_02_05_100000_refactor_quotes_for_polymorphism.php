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
        // Passo 1: Criar a nova tabela para os dados específicos de Carros
        Schema::create('quote_autos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');

            // Campos de Veículo e Risco que estavam na tabela quotes
            $table->string('placa');
            $table->string('chassi');
            $table->string('ano_fabricacao_modelo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('tipo_uso');
            $table->string('tipo_residencia');
            $table->string('garagem_residencia');
            $table->string('garagem_trabalho');
            $table->string('garagem_estudo');
            $table->string('portao_eletronico');
            $table->string('condominio_fechado');
            $table->string('distancia_trabalho');
            $table->string('condutor_menor_26');
            $table->string('km_mensal');
            $table->string('isencao_fiscal');
            $table->string('pcd');
            $table->date('inicio_vigencia');
            $table->string('sinistro_ultimo_ano');

            // Coberturas
            $table->boolean('compreensiva_rcf')->default(false);
            $table->boolean('franquia_reduzida')->default(false);
            $table->boolean('app_morte_invalidez')->default(false);
            $table->boolean('danos_materiais')->default(false);
            $table->boolean('danos_corporais')->default(false);
            $table->boolean('danos_morais')->default(false);
            $table->boolean('carro_reserva')->default(false);
            $table->boolean('assistencia_24h')->default(false);
            $table->boolean('guincho_ilimitado')->default(false);
            $table->boolean('martelinho_ouro')->default(false);
            $table->boolean('isencao_primeira_franquia')->default(false);
            $table->string('plano_vidros')->nullable();

            $table->timestamps();
        });

        // Passo 2: Criar a nova tabela para os dados específicos de Motos
        Schema::create('quote_motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');

            // Especificação da Moto
            $table->string('placa');
            $table->string('chassi');
            $table->string('ano_fabricacao_modelo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('moto_club')->nullable();
            $table->string('periodo_uso');

            // Questionário de Risco
            $table->string('tipo_uso');
            $table->string('tipo_residencia');
            $table->string('garagem_residencia');
            $table->string('garagem_trabalho');
            $table->string('garagem_estudo');
            $table->string('portao_eletronico');
            $table->string('condominio_fechado');
            $table->string('distancia_trabalho');
            $table->string('condutor_menor_26');
            $table->string('km_mensal');
            $table->string('isencao_fiscal');
            $table->string('pcd');

            // Informações Complementares
            $table->date('inicio_vigencia');
            $table->string('sinistro_ultimo_ano');

            // Coberturas
            $table->boolean('compreensiva_rcf')->default(false);
            $table->boolean('franquia_reduzida')->default(false);
            $table->boolean('app_morte_invalidez')->default(false);
            $table->boolean('danos_materiais')->default(false);
            $table->boolean('danos_corporais')->default(false);
            $table->boolean('danos_morais')->default(false);
            $table->boolean('carro_reserva')->default(false); // Moto Reserva
            $table->boolean('assistencia_24h')->default(false);
            $table->boolean('guincho')->default(false);
            $table->boolean('martelinho_ouro')->default(false);
            $table->boolean('isencao_primeira_franquia')->default(false);
            $table->string('plano_vidros')->nullable();

            $table->timestamps();
        });

        // Passo 3: Alterar a tabela 'quotes' original
        Schema::table('quotes', function (Blueprint $table) {
            // Adicionar colunas para o relacionamento polimórfico
            $table->morphs('quotable');

            // Remover todas as colunas que foram movidas para as tabelas específicas
            $table->dropColumn([
                'placa', 'chassi', 'ano_fabricacao_modelo', 'marca', 'modelo',
                'tipo_uso', 'tipo_residencia', 'garagem_residencia', 'garagem_trabalho',
                'garagem_estudo', 'portao_eletronico', 'condominio_fechado', 'distancia_trabalho',
                'condutor_menor_26', 'km_mensal', 'isencao_fiscal', 'pcd', 'inicio_vigencia',
                'sinistro_ultimo_ano', 'compreensiva_rcf', 'franquia_reduzida',
                'app_morte_invalidez', 'danos_materiais', 'danos_corporais', 'danos_morais',
                'carro_reserva', 'assistencia_24h', 'guincho_ilimitado', 'martelinho_ouro',
                'isencao_primeira_franquia', 'plano_vidros'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // O reverso aqui seria complexo, o ideal é recriar a tabela original.
            // Por simplicidade, vamos apenas dropar as colunas e tabelas novas.
            $table->dropMorphs('quotable');
        });

        Schema::dropIfExists('quote_autos');
        Schema::dropIfExists('quote_motorcycles');
    }
};
