<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteDocument extends Model
{
    use HasFactory;

    protected $fillable = ['quote_id', 'caminho_arquivo', 'nome_original'];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
