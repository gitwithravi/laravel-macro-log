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
        'logged_at',
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
            'logged_at' => 'datetime',
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

    /**
     * Get the logged date in the user's timezone.
     */
    public function getLoggedDateInUserTimezone(): string
    {
        if (!$this->logged_at || !$this->user) {
            return '';
        }

        return $this->logged_at->setTimezone($this->user->getUserTimezone())->toDateString();
    }

    /**
     * Get the logged time in the user's timezone.
     */
    public function getLoggedTimeInUserTimezone(): string
    {
        if (!$this->logged_at || !$this->user) {
            return '';
        }

        return $this->logged_at->setTimezone($this->user->getUserTimezone())->format('H:i:s');
    }

    /**
     * Get the logged datetime in the user's timezone.
     */
    public function getLoggedAtInUserTimezone(): \Carbon\Carbon
    {
        if (!$this->logged_at || !$this->user) {
            return now();
        }

        return $this->logged_at->setTimezone($this->user->getUserTimezone());
    }

    /**
     * Accessor for backwards compatibility with logged_date.
     */
    public function getLoggedDateAttribute(): string
    {
        return $this->getLoggedDateInUserTimezone();
    }

    /**
     * Accessor for backwards compatibility with logged_time.
     */
    public function getLoggedTimeAttribute(): string
    {
        return $this->getLoggedTimeInUserTimezone();
    }
}
