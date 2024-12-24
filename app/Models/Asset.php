<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;
    
    protected $table = 'assets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'src',
        'type',
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
