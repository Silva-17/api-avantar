<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMotorcycleQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Proponente
            'tipo_operacao' => 'required|string',
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'cpf_cnpj' => 'required|string',
            'telefone' => 'required|string',
            'email' => 'required|email',
            'cep' => 'required|string',
            'profissao' => 'required|string',
            'estado_civil' => 'required|string',

            // Moto
            'placa' => 'required|string',
            'chassi' => 'required|string',
            'ano_fabricacao_modelo' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'moto_club' => 'nullable|string',
            'periodo_uso' => 'required|string',
            'tipo_uso' => 'required|string',
            'tipo_residencia' => 'required|string',
            'garagem_residencia' => 'required|string',
            'garagem_trabalho' => 'required|string',
            'garagem_estudo' => 'required|string',
            'portao_eletronico' => 'required|string',
            'condominio_fechado' => 'required|string',
            'distancia_trabalho' => 'required|string',
            'condutor_menor_26' => 'required|string',
            'km_mensal' => 'required|string',
            'isencao_fiscal' => 'required|string',
            'pcd' => 'required|string',
            'inicio_vigencia' => 'required|date',
            'sinistro_ultimo_ano' => 'required|string',
            'compreensiva_rcf' => 'boolean',
            'franquia_reduzida' => 'boolean',
            'app_morte_invalidez' => 'boolean',
            'danos_materiais' => 'boolean',
            'danos_corporais' => 'boolean',
            'danos_morais' => 'boolean',
            'carro_reserva' => 'boolean',
            'assistencia_24h' => 'boolean',
            'guincho' => 'boolean',
            'martelinho_ouro' => 'boolean',
            'isencao_primeira_franquia' => 'boolean',
            'plano_vidros' => 'nullable|string',

            // Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
