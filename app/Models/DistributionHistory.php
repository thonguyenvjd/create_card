<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistributionHistory extends Model
{
    use HasFactory;
    
    protected $table = 'distribution_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reservation_id',
        'number_of_delivery',
        'success_count',
        'failed_count',
        'open_count',
        'click_count',
    ];

    public function distributionDetails(): HasMany
    {
        return $this->hasMany(DistributionDetail::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
