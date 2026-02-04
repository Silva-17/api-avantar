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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Consultor

            // 1. Dados do Proponente
            $table->string('tipo_operacao'); // Novo Seguro, Renovação
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->string('sexo'); // Feminino, Masculino
            $table->string('cpf_cnpj');
            $table->string('telefone');
            $table->string('email');
            $table->string('cep');
            $table->string('profissao');
            $table->string('estado_civil');

            // 2. Especificação do Veículo
            $table->string('placa');
            $table->string('chassi');
            $table->string('ano_fabricacao_modelo');
            $table->string('marca');
            $table->string('modelo');

            // 3. Questionário de Risco
            $table->string('tipo_uso');
            $table->string('tipo_residencia');
            $table->string('garagem_residencia'); // Sim/Não
            $table->string('garagem_trabalho'); // Sim/Não
            $table->string('garagem_estudo'); // Sim/Não
            $table->string('portao_eletronico'); // Sim/Não
            $table->string('condominio_fechado'); // Sim/Não
            $table->string('distancia_trabalho'); // KM
            $table->string('condutor_menor_26'); // Sim/Não
            $table->string('km_mensal');
            $table->string('isencao_fiscal'); // Sim/Não
            $table->string('pcd'); // Sim/Não
            $table->date('inicio_vigencia');
            $table->string('sinistro_ultimo_ano'); // SIM, NÃO

            // 4. Matriz de Coberturas
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
            $table->string('plano_vidros')->nullable(); // Básicos, Completo, VIP

            $table->timestamps();
        });

        Schema::create('quote_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->string('caminho_arquivo');
            $table->string('nome_original');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_documents');
        Schema::dropIfExists('quotes');
    }
};
