<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportCardJob extends Model
{
    use HasFactory;
    protected $table = 'export_card_jobs';

    protected $fillable = [
        'user_id',
        'file_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
