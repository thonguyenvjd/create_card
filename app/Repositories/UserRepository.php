<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository implements \App\Contracts\Repositories\UserRepositoryInterface
{
    protected array $allowedFilters = [
        'email',
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function model(): string
    {
        return User::class;
    }
}
