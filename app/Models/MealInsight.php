<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealInsight extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meal_entry_id',
        'insight',
    ];

    /**
     * Get the meal entry that owns the insight.
     */
    public function mealEntry(): BelongsTo
    {
        return $this->belongsTo(MealEntry::class);
    }
}
