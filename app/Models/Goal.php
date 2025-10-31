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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'current_weight' => 'decimal:2',
            'target_weight' => 'decimal:2',
            'daily_goal_calories' => 'integer',
            'daily_goal_protein' => 'decimal:2',
            'daily_goal_carb' => 'decimal:2',
            'daily_goal_fat' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
