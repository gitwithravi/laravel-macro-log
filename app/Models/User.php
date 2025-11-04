<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'email_verified_at',
        'date_of_birth',
        'gender',
        'height',
        'open_api_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'open_api_key',  // Hide API key from serialization
        // Note: We don't hide PII fields as frontend needs them, but they're encrypted
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'encrypted',  // Encrypt PII - date of birth
            'gender' => 'encrypted',  // Encrypt PII - gender
            'height' => 'encrypted',  // Encrypt PII - height
            'open_api_key' => 'encrypted',  // Encrypt API key at rest
        ];
    }

    /**
     * Get the goals for the user.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Get the meal entries for the user.
     */
    public function mealEntries(): HasMany
    {
        return $this->hasMany(MealEntry::class);
    }

    /**
     * Check if the user has completed their profile.
     */
    public function hasCompletedProfile(): bool
    {
        return !is_null($this->date_of_birth)
            && !is_null($this->gender)
            && !is_null($this->height)
            && !is_null($this->open_api_key);
    }

    /**
     * Get formatted date of birth (decrypted automatically by 'encrypted' cast).
     * This accessor ensures date formatting works properly with encryption.
     */
    public function getFormattedDateOfBirthAttribute(): ?string
    {
        if (!$this->date_of_birth) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($this->date_of_birth)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->date_of_birth;
        }
    }

    /**
     * Get numeric height value (decrypted automatically by 'encrypted' cast).
     * This accessor ensures height can be used in calculations.
     */
    public function getHeightNumericAttribute(): ?float
    {
        return $this->height ? (float) $this->height : null;
    }
}
