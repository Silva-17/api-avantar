<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLifeGroupQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Questionário Corporativo
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string',
            'qtd_funcionarios' => 'required|integer',
            'endereco' => 'required|string',
            'ramo_atividade' => 'required|string',

            // 2. Capitais Segurados
            'capital_segurado' => 'required|string',

            // 3. Histórico de Seguro
            'seguro_vigente' => 'required|boolean',
            'vidas_cobertas_atualmente' => 'nullable|integer|required_if:seguro_vigente,true',
            'sinistro_12_meses' => 'required|boolean',

            // 4. Coberturas Desejadas
            'morte_basica' => 'boolean',
            'morte_acidental' => 'boolean',
            'ipa' => 'boolean',
            'ifpd' => 'boolean',
            'funeral' => 'boolean',
            'conjuge_filhos' => 'boolean',

            // 5. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
