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

    /**
     * Atualiza o usuário com os dados fornecidos.
     * @param string $email
     * @param array $data
     * @return object
     */
    public function update(string $email, array $data): object;

    /**
     * Deleta o usuário pelo email
     * @param string $email
     * @return bool
     */
    public function delete(string $email): bool;

    /**
     * Get User by email
     * @param string $email
     * @return object
     */
    public function findByEmail(string $email): object;

}
