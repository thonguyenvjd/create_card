<?php

namespace App\Models;

use App\Models\Traits\HasUserContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory, HasUserContext;
    
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email_setting_id',
        'address_to',
        'address_to_type',
        'subject',
        'content',
        'email_type',
        'delivery_type',
        'delivery_status',
        'scheduled_at',
        'is_draft',
        'is_click_measure',
        'is_sent',
        'attachments',
        'user_id',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function emailSetting(): BelongsTo
    {
        return $this->belongsTo(EmailSetting::class);
    }
}
