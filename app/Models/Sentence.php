<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sentence extends Model
{
    use HasFactory;
 
    protected $table = 'sentences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'content',
        'user_id',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sentence) {
            $sentence->user_id = auth()->user()->id;
        });

        static::updating(function ($sentence) {
            $sentence->user_id = auth()->user()->id;
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
