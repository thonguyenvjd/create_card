<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;
 
    protected $table = 'templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'subject',
        'content',
        'type',
        'image',
        'email_setting_id',
        'address_to',
        'address_to_type',
        'scheduled_at',
        'user_id',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            $template->user_id = auth()->user()->id;
        });

        static::updating(function ($template) {
            $template->user_id = auth()->user()->id;
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
