<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsortiumQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Identificação do Interessado
            'nome_completo' => 'sometimes|required|string|max:255',
            'cpf_cnpj' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'telefone' => 'sometimes|required|string',
            'cep' => 'sometimes|required|string',

            // 2. Questionário de Consórcio
            'tipo_pessoa' => 'sometimes|required|string',
            'cotar_seguro_vida' => 'sometimes|required|boolean',
            'valor_parcela' => 'sometimes|required|numeric',
            'valor_carta_credito' => 'sometimes|required|numeric',
            'tipo_produto' => 'sometimes|required|string',
            'tipo_grupo' => 'sometimes|required|string',

            // 3. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
