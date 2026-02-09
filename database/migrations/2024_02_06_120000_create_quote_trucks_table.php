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
        Schema::create('quote_trucks', function (Blueprint $table) {
            $table->id();

            // 2. Especificações Técnicas
            $table->string('placa');
            $table->string('chassi');
            $table->string('ano_fabricacao_modelo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('rastreador')->nullable();
            $table->string('dispositivo')->nullable();
            $table->string('anti_furto')->nullable();

            // 3. Questionário de Risco
            $table->string('tipo_uso');
            $table->string('tipo_carroceria');
            $table->string('equipamentos')->nullable();
            $table->string('gerenciamento_risco');
            $table->string('tipos_cargas');
            $table->string('seguro_carga');
            $table->string('periodo_uso');
            $table->string('area_circulacao');
            $table->string('pcd');

            // 4. Informações Complementares
            $table->date('inicio_vigencia');
            $table->string('sinistro_ultimo_ano');

            // 5. Matriz de Coberturas
            $table->boolean('compreensiva_rcf')->default(false);
            $table->boolean('franquia_reduzida')->default(false);
            $table->boolean('app_morte_invalidez')->default(false);
            $table->boolean('danos_materiais')->default(false);
            $table->boolean('danos_corporais')->default(false);
            $table->boolean('danos_morais')->default(false);
            $table->boolean('carro_reserva')->default(false); // Caminhão Reserva
            $table->boolean('assistencia_24h')->default(false);
            $table->boolean('guincho')->default(false);
            $table->boolean('martelinho_ouro')->default(false);
            $table->boolean('isencao_primeira_franquia')->default(false);
            $table->string('plano_vidros')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_trucks');
    }
};
