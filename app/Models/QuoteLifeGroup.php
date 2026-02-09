<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuoteLifeGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'seguro_vigente' => 'boolean',
        'sinistro_12_meses' => 'boolean',
        'morte_basica' => 'boolean',
        'morte_acidental' => 'boolean',
        'ipa' => 'boolean',
        'ifpd' => 'boolean',
        'funeral' => 'boolean',
        'conjuge_filhos' => 'boolean',
    ];

    public function quotes(): MorphMany
    {
        return $this->morphMany(Quote::class, 'quotable');
    }
}
