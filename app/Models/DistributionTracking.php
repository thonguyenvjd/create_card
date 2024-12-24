<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionTracking extends Model
{
    use HasFactory;
    
    protected $table = 'distribution_trackings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'distribution_history_id',
        'distribution_detail_id',
        'tracking_token',
        'type',
        'original_url',
        'tracked_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
    ];

    public function distributionDetail(): BelongsTo
    {
        return $this->belongsTo(DistributionDetail::class);
    }

    public function distributionHistory(): BelongsTo
    {
        return $this->belongsTo(DistributionHistory::class);
    }
}
