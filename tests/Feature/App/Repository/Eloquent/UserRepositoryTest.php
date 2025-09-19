<?php

namespace Tests\Feature\App\Repository\Eloquent;

use App\Repository\Eloquent\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $repository = new UserRepository(new User());
        $response = $repository->findAll();

        $this->assertIsArray($response);
    }
}
