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

    /**
     * Cria um novo usuário com os dados fornecidos.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object;
}
