<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    // ============ STATUS CONSTANTS ============
    public const STATUS_NEW      = 'Baru';
    public const STATUS_FOLLOWED = 'Ditindaklanjuti';

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'category',
        'rating',
        'message',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Masukan yang sudah ditindaklanjuti bersifat final (satu arah).
     */
    public function isFollowedUp(): bool
    {
        return $this->status === self::STATUS_FOLLOWED;
    }
}
