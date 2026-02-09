<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Passo 1: Adicionar colunas de proponente às tabelas de veículos
        $proponentCols = function (Blueprint $table) {
            $table->string('tipo_operacao')->after('id');
            $table->string('nome_completo')->after('tipo_operacao');
            $table->date('data_nascimento')->after('nome_completo');
            $table->string('sexo')->after('data_nascimento');
            $table->string('cpf_cnpj')->after('sexo');
            $table->string('telefone')->after('cpf_cnpj');
            $table->string('email')->after('telefone');
            $table->string('cep')->after('email');
            $table->string('profissao')->after('cep');
            $table->string('estado_civil')->after('profissao');
        };

        Schema::table('quote_autos', $proponentCols);
        Schema::table('quote_motorcycles', $proponentCols);
        Schema::table('quote_trucks', $proponentCols);

        // Passo 2: Remover colunas de proponente da tabela 'quotes'
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_operacao', 'nome_completo', 'data_nascimento', 'sexo',
                'cpf_cnpj', 'telefone', 'email', 'cep', 'profissao', 'estado_civil'
            ]);
        });

        // Passo 3: Criar a tabela para Seguro de Vida Individual
        Schema::create('quote_life_individuals', function (Blueprint $table) {
            $table->id();
            // Dados Pessoais
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->string('cpf');
            $table->string('estado_civil');
            $table->string('profissao');
            $table->string('email');
            $table->text('endereco');
            // Informações de Saúde
            $table->text('doencas_pre_existentes')->nullable();
            $table->boolean('fumante')->default(false);
            // Coberturas
            $table->decimal('capital_segurado', 15, 2);
            $table->boolean('morte_qualquer_causa')->default(false);
            $table->boolean('morte_acidental')->default(false);
            $table->boolean('ipa')->default(false);
            $table->boolean('ifpd')->default(false);
            $table->boolean('ilpd')->default(false);
            $table->boolean('doencas_graves')->default(false);
            $table->boolean('dit')->default(false);
            $table->boolean('assistencias')->default(false);
            $table->timestamps();
        });

        // Passo 4: Criar a tabela para os Beneficiários
        Schema::create('quote_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_life_individual_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('cpf');
            $table->string('parentesco');
            $table->unsignedInteger('percentual');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // O reverso é complexo, mas basicamente desfazemos os passos
        Schema::dropIfExists('quote_beneficiaries');
        Schema::dropIfExists('quote_life_individuals');

        Schema::table('quotes', function (Blueprint $table) {
            $table->string('tipo_operacao');
            $table->string('nome_completo');
            // ... etc, recriar as colunas
        });

        // Remover colunas das tabelas de veículos
    }
};
