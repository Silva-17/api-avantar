<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLifeIndividualQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Dados Pessoais
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string',
            'estado_civil' => 'required|string',
            'profissao' => 'required|string',
            'email' => 'required|email',
            'endereco' => 'required|string',

            // 2. Informações de Saúde
            'doencas_pre_existentes' => 'nullable|string',
            'fumante' => 'required|boolean',

            // 3. Coberturas
            'capital_segurado' => 'required|numeric',
            'morte_qualquer_causa' => 'boolean',
            'morte_acidental' => 'boolean',
            'ipa' => 'boolean',
            'ifpd' => 'boolean',
            'ilpd' => 'boolean',
            'doencas_graves' => 'boolean',
            'dit' => 'boolean',
            'assistencias' => 'boolean',

            // 4. Beneficiários
            'beneficiarios' => 'required|array|min:1',
            'beneficiarios.*.nome' => 'required|string|max:255',
            'beneficiarios.*.cpf' => 'required|string',
            'beneficiarios.*.parentesco' => 'required|string',
            'beneficiarios.*.percentual' => 'required|numeric|min:1|max:100',

            // 5. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
