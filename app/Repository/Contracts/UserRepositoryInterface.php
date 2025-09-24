<?php

namespace App\Repository\Contracts;

interface UserRepositoryInterface
{
    /**
     * Retorna todos os registros do repositório como array.
     *
     * @return array
     */
    public function findAll(): array;
}
