<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipientFilter extends Model
{
    use HasFactory;
 
    protected $table = 'recipient_filters';

    protected $fillable = [
        'user_id',
        'name',
        'conditions'
    ];

    protected $casts = [
        'conditions' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($recipientFilter) {
            $recipientFilter->user_id = auth()->user()->id;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
