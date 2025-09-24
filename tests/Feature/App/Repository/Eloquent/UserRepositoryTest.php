<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Repository\Eloquent\UserRepository;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRepositoryTest extends TestCase
{
    /**
     * Testes para a implementação Eloquent de UserRepository.
     *
     * Descrição geral:
     * - Exercita a implementação concreta do repositório de usuários, verificando conformidade com a interface,
     *   comportamento de leitura (findAll) em cenários vazio e populado, e integração de criação de usuário.
     * - Assume o ambiente de testes do Laravel (factories, migrations/refresh da base de testes).
     *
     * setUp():
     * - Inicializa uma instância de UserRepository com um modelo User antes de cada teste.
     *
     * test_implements_interface():
     * - Objetivo: garantir que a implementação concreta respeita o contrato definido por UserRepositoryInterface.
     * - Verifica que o repositório é uma instância da interface esperada.
     *
     * test_find_all_empty():
     * - Cenário: base de dados sem usuários.
     * - Comportamento esperado: findAll() retorna um array, que deve estar vazio (count = 0).
     *
     * test_find_all():
     * - Cenário: popula-se a base de testes com 5 usuários via factory.
     * - Comportamento esperado: findAll() retorna um array contendo exatamente 5 elementos.
     *
     * test_create_user():
     * - Tipo: teste de integração para criação de usuário pelo repositório.
     * - Fluxo: fornece dados válidos (nome, email, senha já hasheada), chama create() e valida:
     *     - o retorno não é nulo e é um objeto (representando o modelo criado);
     *     - a tabela 'users' contém um registro com o email fornecido.
     * - Observação: a senha deve ser persistida de forma segura (hash) — no teste é usada bcrypt() para simular o input.
     *
     * Observações adicionais:
     * - Estes testes combinam assertivas unitárias (estrutura/contrato) com verificações de integração (persistência).
     * - Recomenda-se garantir isolamento entre testes (rollback ou RefreshDatabase) para manter reprodutibilidade.
     */
    protected $repository;
    protected function setUp(): void
    {
        $this->repository = new UserRepository(new User());
        parent::setUp();
    }

    public function test_implements_interface(): void
    {
        $this->assertInstanceOf(UserRepositoryInterface::class, $this->repository);
    }

    public function test_find_all_empty(): void
    {
        $repository = $this->repository;
        $response = $repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(0, $response);
    }

    public function test_find_all(): void
    {
        User::factory()->count(5)->create();

        $repository = $this->repository;
        $response = $repository->findAll();

        $this->assertIsArray($response);
        $this->assertCount(5, $response);
    }

    public function test_create_user(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ];

        $response = $this->repository->create($data);
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com'
        ]);
    }
}
