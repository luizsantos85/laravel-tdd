<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    protected string $endPoint = '/api/users';

    // public function test_get_all_empty(): void
    // {
    //     $response = $this->getJson($this->endPoint);
    //     $response->assertStatus(Response::HTTP_OK);
    // }

    // public function test_get_all(): void
    // {
    //     User::factory()->count(20)->create();

    //     $response = $this->getJson($this->endPoint);
    //     $response->dump();
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertJsonCount(20, 'data');
    // }

    public function test_paginate_empty(): void
    {
        $response = $this->getJson($this->endPoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
        $response->assertJsonPath('meta.total', 0);
    }

    public function test_paginate(): void
    {
        User::factory()->count(40)->create();

        $response = $this->getJson($this->endPoint);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ]
        ]);
        $response->assertJsonPath('meta.total', 40);
        $response->assertJsonPath('meta.current_page', 1);
    }

    public function test_paginate_page_two(): void
    {
        User::factory()->count(20)->create();

        $response = $this->getJson("{$this->endPoint}?page=2");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonPath('meta.total', 20);
        $response->assertJsonPath('meta.current_page', 2);
    }
}
