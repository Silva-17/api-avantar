<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentialQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Dados Iniciais
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'required|string',
            'cep' => 'required|string',
            'seguro_novo' => 'required|string',
            'seguradora_anterior' => 'nullable|string',

            // 2. Especificação Residencial
            'endereco_completo' => 'required|string',
            'segmento' => 'required|string',
            'tipo_construcao' => 'required|string',
            'tipo_logradouro' => 'required|string',
            'tipo_residencia' => 'required|string',

            // 3. Avaliação de Risco
            'alarme_roubo' => 'required|boolean',
            'predio_conteudo' => 'required|boolean',
            'grades_janela' => 'required|boolean',
            'proprietario_imovel' => 'required|boolean',
            'zona_rural' => 'required|boolean',
            'condominio_fechado' => 'required|string',
            'valor_novo' => 'required|numeric',
            'valor_imovel' => 'required|numeric',
            'clausula_beneficiaria' => 'nullable|string',

            // 4. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
