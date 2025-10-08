<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\PaginationInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exception\NotFoundException;
use App\Repository\Presenters\PaginationPresenter;

class UserRepository implements UserRepositoryInterface
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

    public function paginate(int $page = 1): PaginationInterface
    {
        return new PaginationPresenter($this->model->paginate());
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(string $email, array $data): object
    {
        $user = $this->model->where('email', $email)->first();
        $user->update($data);
        $user->refresh();

        return $user;
    }

    public function delete(string $email): bool
    {
        $user = $this->findByEmail($email);
        return $user->delete();
    }

    public function findByEmail(string $email): object
    {
        $user = $this->model->where('email', $email)->first();
        if (!$user) throw new NotFoundException("User not found");
        return $user;
    }
}
