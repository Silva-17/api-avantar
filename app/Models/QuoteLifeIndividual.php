<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuoteLifeIndividual extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data_nascimento' => 'date',
        'fumante' => 'boolean',
        'capital_segurado' => 'decimal:2',
        'morte_qualquer_causa' => 'boolean',
        'morte_acidental' => 'boolean',
        'ipa' => 'boolean',
        'ifpd' => 'boolean',
        'ilpd' => 'boolean',
        'doencas_graves' => 'boolean',
        'dit' => 'boolean',
        'assistencias' => 'boolean',
    ];

    public function quotes(): MorphMany
    {
        return $this->morphMany(Quote::class, 'quotable');
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(QuoteBeneficiary::class);
    }
}
