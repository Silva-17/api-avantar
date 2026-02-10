<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuoteResidential extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'alarme_roubo' => 'boolean',
        'predio_conteudo' => 'boolean',
        'grades_janela' => 'boolean',
        'proprietario_imovel' => 'boolean',
        'zona_rural' => 'boolean',
        'valor_novo' => 'decimal:2',
        'valor_imovel' => 'decimal:2',
    ];

    public function quotes(): MorphMany
    {
        return $this->morphMany(Quote::class, 'quotable');
    }
}
