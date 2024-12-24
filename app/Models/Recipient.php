<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\HasUserContext;

class Recipient extends Model
{
    use HasFactory, SoftDeletes, HasUserContext;
 
    protected $table = 'recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'number_of_error',
        'situation',
        'user_id',
    ];
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function recipientAssigns()
    {
        return $this->hasMany(RecipientAssign::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'recipient_assigns');
    }
}
