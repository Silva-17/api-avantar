<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentialQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Dados Iniciais
            'razao_social' => 'sometimes|required|string|max:255',
            'cnpj' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'telefone' => 'sometimes|required|string',
            'cep' => 'sometimes|required|string',
            'seguro_novo' => 'sometimes|required|string',
            'seguradora_anterior' => 'nullable|string',

            // 2. Especificação Residencial
            'endereco_completo' => 'sometimes|required|string',
            'segmento' => 'sometimes|required|string',
            'tipo_construcao' => 'sometimes|required|string',
            'tipo_logradouro' => 'sometimes|required|string',
            'tipo_residencia' => 'sometimes|required|string',

            // 3. Avaliação de Risco
            'alarme_roubo' => 'sometimes|required|boolean',
            'predio_conteudo' => 'sometimes|required|boolean',
            'grades_janela' => 'sometimes|required|boolean',
            'proprietario_imovel' => 'sometimes|required|boolean',
            'zona_rural' => 'sometimes|required|boolean',
            'condominio_fechado' => 'sometimes|required|string',
            'valor_novo' => 'sometimes|required|numeric',
            'valor_imovel' => 'sometimes|required|numeric',
            'clausula_beneficiaria' => 'nullable|string',

            // 4. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
