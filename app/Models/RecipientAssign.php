<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientAssign extends Model
{
    use HasFactory;
 
    protected $table = 'recipient_assigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recipient_id',
        'group_id',
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
