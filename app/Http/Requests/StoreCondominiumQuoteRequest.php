<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCondominiumQuoteRequest extends FormRequest
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

            // 2. Especificações do Condomínio
            'tipo_condominio' => 'required|string',
            'segmento' => 'required|string',
            'tipo_construcao' => 'required|string',
            'ano_construcao' => 'required|date',
            'endereco_completo' => 'required|string',
            'num_blocos' => 'required|integer',
            'pavimentos' => 'required|integer',

            // 3. Avaliação de Riscos
            'alarme' => 'required|boolean',
            'areas_lazer' => 'required|boolean',
            'circuito_fechado' => 'required|boolean',
            'predio_conteudo' => 'required|boolean',
            'condominio_fechado' => 'required|boolean',
            'elevador' => 'required|boolean',
            'equip_seguranca' => 'required|boolean',
            'funcionarios_proprios' => 'required|boolean',
            'num_funcionarios' => 'nullable|integer|required_if:funcionarios_proprios,true',
            'grade_fechadura' => 'required|boolean',
            'sinistro_12_meses' => 'required|boolean',
            'portao_automatico' => 'required|boolean',
            'reaproveitamento_agua' => 'required|boolean',
            'sensor_infra' => 'required|boolean',
            'vaga_visitante' => 'required|boolean',
            'valor_novo' => 'required|boolean',
            'valor_imovel' => 'required|boolean',
            'vigilancia_24h' => 'required|boolean',

            // 4. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
