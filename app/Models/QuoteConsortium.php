<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuoteConsortium extends Model
{
    use HasFactory;

    protected $table = 'quote_consortia';

    protected $guarded = ['id'];

    protected $casts = [
        'cotar_seguro_vida' => 'boolean',
        'valor_parcela' => 'decimal:2',
        'valor_carta_credito' => 'decimal:2',
    ];

    public function quotes(): MorphMany
    {
        return $this->morphMany(Quote::class, 'quotable');
    }
}
