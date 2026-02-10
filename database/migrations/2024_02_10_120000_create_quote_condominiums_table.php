<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_condominiums', function (Blueprint $table) {
            $table->id();

            // 1. Dados Iniciais
            $table->string('razao_social');
            $table->string('cnpj');
            $table->string('email');
            $table->string('telefone');
            $table->string('cep');
            $table->string('seguro_novo');
            $table->string('seguradora_anterior')->nullable();

            // 2. Especificações do Condomínio
            $table->string('tipo_condominio');
            $table->string('segmento');
            $table->string('tipo_construcao');
            $table->date('ano_construcao');
            $table->text('endereco_completo');
            $table->integer('num_blocos');
            $table->integer('pavimentos');

            // 3. Avaliação de Riscos
            $table->boolean('alarme')->default(false);
            $table->boolean('areas_lazer')->default(false);
            $table->boolean('circuito_fechado')->default(false);
            $table->boolean('predio_conteudo')->default(false);
            $table->boolean('condominio_fechado')->default(false);
            $table->boolean('elevador')->default(false);
            $table->boolean('equip_seguranca')->default(false);
            $table->boolean('funcionarios_proprios')->default(false);
            $table->integer('num_funcionarios')->nullable();
            $table->boolean('grade_fechadura')->default(false);
            $table->boolean('sinistro_12_meses')->default(false);
            $table->boolean('portao_automatico')->default(false);
            $table->boolean('reaproveitamento_agua')->default(false);
            $table->boolean('sensor_infra')->default(false);
            $table->boolean('vaga_visitante')->default(false);
            $table->boolean('valor_novo')->default(false); // Seguindo a especificação, mas geralmente é um valor numérico
            $table->boolean('valor_imovel')->default(false); // Seguindo a especificação, mas geralmente é um valor numérico
            $table->boolean('vigilancia_24h')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_condominiums');
    }
};
