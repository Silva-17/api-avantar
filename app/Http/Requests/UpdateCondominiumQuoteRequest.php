<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCondominiumQuoteRequest extends FormRequest
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

            // 2. Especificações do Condomínio
            'tipo_condominio' => 'sometimes|required|string',
            'segmento' => 'sometimes|required|string',
            'tipo_construcao' => 'sometimes|required|string',
            'ano_construcao' => 'sometimes|required|date',
            'endereco_completo' => 'sometimes|required|string',
            'num_blocos' => 'sometimes|required|integer',
            'pavimentos' => 'sometimes|required|integer',

            // 3. Avaliação de Riscos
            'alarme' => 'sometimes|required|boolean',
            'areas_lazer' => 'sometimes|required|boolean',
            'circuito_fechado' => 'sometimes|required|boolean',
            'predio_conteudo' => 'sometimes|required|boolean',
            'condominio_fechado' => 'sometimes|required|boolean',
            'elevador' => 'sometimes|required|boolean',
            'equip_seguranca' => 'sometimes|required|boolean',
            'funcionarios_proprios' => 'sometimes|required|boolean',
            'num_funcionarios' => 'nullable|integer|required_if:funcionarios_proprios,true',
            'grade_fechadura' => 'sometimes|required|boolean',
            'sinistro_12_meses' => 'sometimes|required|boolean',
            'portao_automatico' => 'sometimes|required|boolean',
            'reaproveitamento_agua' => 'sometimes|required|boolean',
            'sensor_infra' => 'sometimes|required|boolean',
            'vaga_visitante' => 'sometimes|required|boolean',
            'valor_novo' => 'sometimes|required|boolean',
            'valor_imovel' => 'sometimes|required|boolean',
            'vigilancia_24h' => 'sometimes|required|boolean',

            // 4. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
