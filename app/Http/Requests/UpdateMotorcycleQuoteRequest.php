<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMotorcycleQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Proponente
            'tipo_operacao' => 'sometimes|required|string',
            'nome_completo' => 'sometimes|required|string|max:255',
            'data_nascimento' => 'sometimes|required|date',
            'sexo' => 'sometimes|required|string',
            'cpf_cnpj' => 'sometimes|required|string',
            'telefone' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'cep' => 'sometimes|required|string',
            'profissao' => 'sometimes|required|string',
            'estado_civil' => 'sometimes|required|string',

            // Moto
            'placa' => 'sometimes|required|string',
            'chassi' => 'sometimes|required|string',
            'ano_fabricacao_modelo' => 'sometimes|required|string',
            'marca' => 'sometimes|required|string',
            'modelo' => 'sometimes|required|string',
            'moto_club' => 'nullable|string',
            'periodo_uso' => 'sometimes|required|string',
            'tipo_uso' => 'sometimes|required|string',
            'tipo_residencia' => 'sometimes|required|string',
            'garagem_residencia' => 'sometimes|required|string',
            'garagem_trabalho' => 'sometimes|required|string',
            'garagem_estudo' => 'sometimes|required|string',
            'portao_eletronico' => 'sometimes|required|string',
            'condominio_fechado' => 'sometimes|required|string',
            'distancia_trabalho' => 'sometimes|required|string',
            'condutor_menor_26' => 'sometimes|required|string',
            'km_mensal' => 'sometimes|required|string',
            'isencao_fiscal' => 'sometimes|required|string',
            'pcd' => 'sometimes|required|string',
            'inicio_vigencia' => 'sometimes|required|date',
            'sinistro_ultimo_ano' => 'sometimes|required|string',
            'compreensiva_rcf' => 'sometimes|boolean',
            'franquia_reduzida' => 'sometimes|boolean',
            'app_morte_invalidez' => 'sometimes|boolean',
            'danos_materiais' => 'sometimes|boolean',
            'danos_corporais' => 'sometimes|boolean',
            'danos_morais' => 'sometimes|boolean',
            'carro_reserva' => 'sometimes|boolean',
            'assistencia_24h' => 'sometimes|boolean',
            'guincho' => 'sometimes|boolean',
            'martelinho_ouro' => 'sometimes|boolean',
            'isencao_primeira_franquia' => 'sometimes|boolean',
            'plano_vidros' => 'nullable|string',

            // Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
