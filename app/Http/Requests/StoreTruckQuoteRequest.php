<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTruckQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Proponente (Reaproveitado)
            'tipo_operacao' => 'required|string',
            'nome_completo' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|string',
            'cpf_cnpj' => 'required|string',
            'telefone' => 'required|string',
            'email' => 'required|email',
            'cep' => 'required|string',
            'profissao' => 'required|string',
            'estado_civil' => 'required|string',

            // 2. Especificações Técnicas
            'placa' => 'required|string',
            'chassi' => 'required|string',
            'ano_fabricacao_modelo' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'rastreador' => 'nullable|string',
            'dispositivo' => 'nullable|string',
            'anti_furto' => 'nullable|string',

            // 3. Questionário de Risco
            'tipo_uso' => 'required|string',
            'tipo_carroceria' => 'required|string',
            'equipamentos' => 'nullable|string',
            'gerenciamento_risco' => 'required|string',
            'tipos_cargas' => 'required|string',
            'seguro_carga' => 'required|string',
            'periodo_uso' => 'required|string',
            'area_circulacao' => 'required|string',
            'pcd' => 'required|string',

            // 4. Informações Complementares
            'inicio_vigencia' => 'required|date',
            'sinistro_ultimo_ano' => 'required|string',

            // 5. Coberturas
            'compreensiva_rcf' => 'boolean',
            'franquia_reduzida' => 'boolean',
            'app_morte_invalidez' => 'boolean',
            'danos_materiais' => 'boolean',
            'danos_corporais' => 'boolean',
            'danos_morais' => 'boolean',
            'carro_reserva' => 'boolean',
            'assistencia_24h' => 'boolean',
            'guincho' => 'boolean',
            'martelinho_ouro' => 'boolean',
            'isencao_primeira_franquia' => 'boolean',
            'plano_vidros' => 'nullable|string',

            // 6. Documentos
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|max:10240',
        ];
    }
}
