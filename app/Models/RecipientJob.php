<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecipientJob extends Model
{
    use HasFactory;
 
    protected $table = 'recipient_jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'success_count',
        'error_count',
        'error_message',
        'file_path',
        'status',
        'user_id',
    ];
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
