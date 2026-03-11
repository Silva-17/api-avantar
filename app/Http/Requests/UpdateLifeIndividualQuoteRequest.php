<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLifeIndividualQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Dados Pessoais
            'nome_completo' => 'sometimes|required|string|max:255',
            'data_nascimento' => 'sometimes|required|date',
            'cpf' => 'sometimes|required|string',
            'estado_civil' => 'sometimes|required|string',
            'profissao' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'endereco' => 'sometimes|required|string',

            // 2. Informações de Saúde
            'doencas_pre_existentes' => 'nullable|string',
            'fumante' => 'sometimes|required|boolean',

            // 3. Coberturas
            'capital_segurado' => 'sometimes|required|numeric',
            'morte_qualquer_causa' => 'boolean',
            'morte_acidental' => 'boolean',
            'ipa' => 'boolean',
            'ifpd' => 'boolean',
            'ilpd' => 'boolean',
            'doencas_graves' => 'boolean',
            'dit' => 'boolean',
            'assistencias' => 'boolean',

            // 4. Beneficiários (Note: Controller update logic currently doesn't update relations, but validation is here)
            'beneficiarios' => 'sometimes|required|array|min:1',
            'beneficiarios.*.nome' => 'required_with:beneficiarios|string|max:255',
            'beneficiarios.*.cpf' => 'required_with:beneficiarios|string',
            'beneficiarios.*.parentesco' => 'required_with:beneficiarios|string',
            'beneficiarios.*.percentual' => 'required_with:beneficiarios|numeric|min:1|max:100',

            // 5. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
