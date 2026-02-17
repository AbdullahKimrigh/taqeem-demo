<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageCase extends Model
{
    protected $table = 'cases';

    protected $fillable = [
        'accident_number',
        'plate_number',
        'status',
        'service_fee_paid',
        'ai_result',
        'final_result',
        'notes',
        'evaluator_name',
    ];

    protected $casts = [
        'service_fee_paid' => 'boolean',
        'ai_result' => 'array',
        'final_result' => 'array',
    ];

    public const STATUS_SUBMITTED = 'Submitted';
    public const STATUS_READY = 'Ready for Assessment';
    public const STATUS_NEEDS_IMAGES = 'Needs More Images';
    public const STATUS_ESCALATED = 'Escalated';
    public const STATUS_PHYSICAL_VISIT = 'Require Physical Visit';
    public const STATUS_COMPLETED = 'Completed';

    public function images(): HasMany
    {
        return $this->hasMany(CaseImage::class, 'case_id');
    }

    public function getDamagesForDisplay(): array
    {
        $result = $this->final_result ?? $this->ai_result ?? [];
        return $result['damages'] ?? [];
    }

    public function getTotalCost(): float
    {
        $result = $this->final_result ?? $this->ai_result ?? [];
        return (float) ($result['estimated_total'] ?? 0);
    }
}
