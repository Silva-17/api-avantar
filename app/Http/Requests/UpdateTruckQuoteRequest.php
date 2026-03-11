<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTruckQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Proponente (Reaproveitado)
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

            // 2. Especificações Técnicas
            'placa' => 'sometimes|required|string',
            'chassi' => 'sometimes|required|string',
            'ano_fabricacao_modelo' => 'sometimes|required|string',
            'marca' => 'sometimes|required|string',
            'modelo' => 'sometimes|required|string',
            'rastreador' => 'nullable|string',
            'dispositivo' => 'nullable|string',
            'anti_furto' => 'nullable|string',

            // 3. Questionário de Risco
            'tipo_uso' => 'sometimes|required|string',
            'tipo_carroceria' => 'sometimes|required|string',
            'equipamentos' => 'nullable|string',
            'gerenciamento_risco' => 'sometimes|required|string',
            'tipos_cargas' => 'sometimes|required|string',
            'seguro_carga' => 'sometimes|required|string',
            'periodo_uso' => 'sometimes|required|string',
            'area_circulacao' => 'sometimes|required|string',
            'pcd' => 'sometimes|required|string',

            // 4. Informações Complementares
            'inicio_vigencia' => 'sometimes|required|date',
            'sinistro_ultimo_ano' => 'sometimes|required|string',

            // 5. Coberturas
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

            // 6. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
