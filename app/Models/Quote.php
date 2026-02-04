<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data_nascimento' => 'date',
        'inicio_vigencia' => 'date',
        'compreensiva_rcf' => 'boolean',
        'franquia_reduzida' => 'boolean',
        'app_morte_invalidez' => 'boolean',
        'danos_materiais' => 'boolean',
        'danos_corporais' => 'boolean',
        'danos_morais' => 'boolean',
        'carro_reserva' => 'boolean',
        'assistencia_24h' => 'boolean',
        'guincho_ilimitado' => 'boolean',
        'martelinho_ouro' => 'boolean',
        'isencao_primeira_franquia' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(QuoteDocument::class);
    }
}
