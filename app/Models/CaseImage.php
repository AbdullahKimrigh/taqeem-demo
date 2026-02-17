<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseImage extends Model
{
    protected $table = 'images';

    protected $fillable = ['case_id', 'path'];

    public function case(): BelongsTo
    {
        return $this->belongsTo(DamageCase::class, 'case_id');
    }
}
