<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailSetting extends Model
{
    use HasFactory;
    
    protected $table = 'email_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from_name',
        'from_address',
        'username',
        'password',
        'host',
        'port',
        'encryption',
        'cc_email',
        'user_id',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            $asset->user_id = auth()->user()->id;
        });

        static::updating(function ($asset) {
            $asset->user_id = auth()->user()->id;
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
