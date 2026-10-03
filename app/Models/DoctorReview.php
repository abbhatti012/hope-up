<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorReview extends Model
{
    use HasFactory, FormatsDates;

    protected $fillable = [
        'doctor_id',
        'reviewer_id',
        'comments',
        'rating',
        'rating_type',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the doctor being reviewed
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the reviewer
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the rating type options
     */
    public static function getRatingTypeOptions()
    {
        return [
            'excellent' => 'Excellent',
            'very_good' => 'Very Good',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'bad' => 'Bad'
        ];
    }

    /**
     * Get the rating type label
     */
    public function getRatingTypeLabelAttribute()
    {
        $options = self::getRatingTypeOptions();
        return $options[$this->rating_type] ?? $this->rating_type;
    }

    /**
     * Get the rating stars HTML
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="ri-star-fill text-warning"></i>';
            } else {
                $stars .= '<i class="ri-star-line text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Scope to get approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get reviews for a specific doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Get average rating for a doctor
     */
    public static function getAverageRating($doctorId)
    {
        return self::where('doctor_id', $doctorId)
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count for a doctor
     */
    public static function getTotalReviews($doctorId)
    {
        return self::where('doctor_id', $doctorId)
            ->where('is_approved', true)
            ->count();
    }
} 