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
}
