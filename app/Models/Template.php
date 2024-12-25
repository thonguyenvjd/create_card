<?php

namespace App\Models;

use App\Models\Traits\HasUserContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory, HasUserContext;
 
    protected $table = 'templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'image',
        'user_id',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
