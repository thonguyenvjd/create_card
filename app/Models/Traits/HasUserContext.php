<?php

namespace App\Models\Traits;

trait HasUserContext
{
    public static function bootHasUserContext()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }
} 
