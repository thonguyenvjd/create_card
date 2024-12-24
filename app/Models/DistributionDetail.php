<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionDetail extends Model
{
    use HasFactory;
    
    protected $table = 'distribution_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'distribution_history_id',
        'email',
        'status',
        'error_message',
        'sent_at',
        'opened_at',
        'click_count',
        'url_clicked',
    ];

    public function distributionHistory(): BelongsTo
    {
        return $this->belongsTo(DistributionHistory::class);
    }
}
