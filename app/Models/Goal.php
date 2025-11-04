<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'current_weight',
        'target_weight',
        'daily_goal_calories',
        'daily_goal_protein',
        'daily_goal_carb',
        'daily_goal_fat',
        'is_active',
        'goal_target_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'current_weight' => 'encrypted',  // Encrypt PII - current weight
            'target_weight' => 'encrypted',  // Encrypt PII - target weight
            'daily_goal_calories' => 'integer',
            'daily_goal_protein' => 'decimal:2',
            'daily_goal_carb' => 'decimal:2',
            'daily_goal_fat' => 'decimal:2',
            'is_active' => 'boolean',
            'goal_target_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get numeric current weight value (decrypted automatically by 'encrypted' cast).
     * This accessor ensures weight can be used in calculations.
     */
    public function getCurrentWeightNumericAttribute(): ?float
    {
        return $this->current_weight ? (float) $this->current_weight : null;
    }

    /**
     * Get numeric target weight value (decrypted automatically by 'encrypted' cast).
     * This accessor ensures weight can be used in calculations.
     */
    public function getTargetWeightNumericAttribute(): ?float
    {
        return $this->target_weight ? (float) $this->target_weight : null;
    }
}
