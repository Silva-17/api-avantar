<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_life_individual_id',
        'nome',
        'cpf',
        'parentesco',
        'percentual',
    ];

    public function lifeIndividualQuote(): BelongsTo
    {
        return $this->belongsTo(QuoteLifeIndividual::class);
    }
}
