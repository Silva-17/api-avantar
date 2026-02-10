<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsortiumQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Identificação do Interessado
            'nome_completo' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'required|string',
            'cep' => 'required|string',

            // 2. Questionário de Consórcio
            'tipo_pessoa' => 'required|string',
            'cotar_seguro_vida' => 'required|boolean',
            'valor_parcela' => 'required|numeric',
            'valor_carta_credito' => 'required|numeric',
            'tipo_produto' => 'required|string',
            'tipo_grupo' => 'required|string',

            // 3. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
