<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteStatus extends Model
{
    // Desativamos o auto-incremento para podermos usar o ID 0 se o banco permitir,
    // ou simplesmente para ter controle total dos IDs.
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['id', 'name'];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
