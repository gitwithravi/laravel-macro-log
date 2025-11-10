<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequentMeal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'meal_name',
        'raw_input',
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
            'calories' => 'integer',
            'protein' => 'decimal:2',
            'carbs' => 'decimal:2',
            'fat' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the frequent meal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
