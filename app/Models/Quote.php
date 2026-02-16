<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    /**
     * Get the parent quotable model (auto or motorcycle).
     */
    public function quotable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(QuoteDocument::class);
    }

    /**
     * Get the status of the quote.
     */
    public function quoteStatus(): BelongsTo
    {
        return $this->belongsTo(QuoteStatus::class);
    }
}
