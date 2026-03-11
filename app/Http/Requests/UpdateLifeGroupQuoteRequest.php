<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLifeGroupQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Questionário Corporativo
            'razao_social' => 'sometimes|required|string|max:255',
            'cnpj' => 'sometimes|required|string',
            'qtd_funcionarios' => 'sometimes|required|integer',
            'endereco' => 'sometimes|required|string',
            'ramo_atividade' => 'sometimes|required|string',

            // 2. Capitais Segurados
            'capital_segurado' => 'sometimes|required|string',

            // 3. Histórico de Seguro
            'seguro_vigente' => 'sometimes|required|boolean',
            'vidas_cobertas_atualmente' => 'nullable|integer|required_if:seguro_vigente,true',
            'sinistro_12_meses' => 'sometimes|required|boolean',

            // 4. Coberturas Desejadas
            'morte_basica' => 'sometimes|boolean',
            'morte_acidental' => 'sometimes|boolean',
            'ipa' => 'sometimes|boolean',
            'ifpd' => 'sometimes|boolean',
            'funeral' => 'sometimes|boolean',
            'conjuge_filhos' => 'sometimes|boolean',

            // 5. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
