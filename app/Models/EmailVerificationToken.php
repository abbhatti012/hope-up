<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Get the user that owns the verification token.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new verification token
     *
     * @param User $user
     * @param string $email
     * @return EmailVerificationToken
     */
    public static function generateToken(User $user, string $email): EmailVerificationToken
    {
        // Invalidate any existing tokens for this user and email
        self::where('user_id', $user->id)
            ->where('email', $email)
            ->update(['is_used' => true]);

        // Create new token
        return self::create([
            'user_id' => $user->id,
            'email' => $email,
            'token' => Str::random(64),
            'expires_at' => Carbon::now()->addHours(24),
            'is_used' => false,
        ]);
    }

    /**
     * Find a valid token
     *
     * @param string $token
     * @return EmailVerificationToken|null
     */
    public static function findValidToken(string $token): ?EmailVerificationToken
    {
        return self::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    /**
     * Mark token as used
     *
     * @return bool
     */
    public function markAsUsed(): bool
    {
        return $this->update(['is_used' => true]);
    }

    /**
     * Check if token is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
