<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MealEntry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'logged_date',
        'logged_time',
        'raw_input',
        'meal_name',
        'calories',
        'protein',
        'carbs',
        'fat',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_date' => 'date',
            'logged_time' => 'datetime:H:i',
            'calories' => 'integer',
            'protein' => 'decimal:2',
            'carbs' => 'decimal:2',
            'fat' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the meal entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the insight for the meal entry.
     */
    public function insight(): HasOne
    {
        return $this->hasOne(MealInsight::class);
    }
}
