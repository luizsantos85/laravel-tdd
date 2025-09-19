<?php

namespace App\Repository\Eloquent;

use App\Models\User;
// use App\Repository\UserRepositoryInterface;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findAll(): array
    {
        return $this->model->get()->toArray();
    }
}
