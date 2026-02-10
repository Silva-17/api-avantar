<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuoteCondominium extends Model
{
    use HasFactory;

    protected $table = 'quote_condominiums';

    protected $guarded = ['id'];

    protected $casts = [
        'ano_construcao' => 'date',
        'alarme' => 'boolean',
        'areas_lazer' => 'boolean',
        'circuito_fechado' => 'boolean',
        'predio_conteudo' => 'boolean',
        'condominio_fechado' => 'boolean',
        'elevador' => 'boolean',
        'equip_seguranca' => 'boolean',
        'funcionarios_proprios' => 'boolean',
        'grade_fechadura' => 'boolean',
        'sinistro_12_meses' => 'boolean',
        'portao_automatico' => 'boolean',
        'reaproveitamento_agua' => 'boolean',
        'sensor_infra' => 'boolean',
        'vaga_visitante' => 'boolean',
        'valor_novo' => 'boolean', // Manter como boolean conforme especificado
        'valor_imovel' => 'boolean', // Manter como boolean conforme especificado
        'vigilancia_24h' => 'boolean',
    ];

    public function quotes(): MorphMany
    {
        return $this->morphMany(Quote::class, 'quotable');
    }
}
