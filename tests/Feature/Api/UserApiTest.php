<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
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

    // public function test_paginate_empty(): void
    // {
    //     $response = $this->getJson($this->endPoint);
    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertJsonCount(0, 'data');
    //     $response->assertJsonStructure([
    //         'meta' => [
    //             'total',
    //             'current_page',
    //             'first_page',
    //             'last_page',
    //             'per_page',
    //         ],
    //         'data'
    //     ]);
    //     // $response->assertJsonPath('meta.total', 0);
    //     $response->assertJsonFragment([
    //         'total' => 0,
    //     ]);
    // }

    #[DataProvider('dataProviderPagination')] // DataProvider do PHPUnit 9.3+
    public function test_paginate(int $total, int $page = 1, int $totalItemsPage = 15): void
    {
        User::factory()->count($total)->create();

        $response = $this->getJson("{$this->endPoint}?page={$page}");
        // $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $response->assertJsonCount($totalItemsPage, 'data');
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'first_page',
                'last_page',
                'per_page',
            ],
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ]
        ]);
        // $response->assertJsonPath('meta.total', 40);
        // $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonFragment([
            'total' => $total,
            'current_page' => $page,
        ]);
    }

    public function test_paginate_page_two(): void
    {
        User::factory()->count(20)->create();

        $response = $this->getJson("{$this->endPoint}?page=2");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(5, 'data');
        // $response->assertJsonPath('meta.total', 20);
        // $response->assertJsonPath('meta.current_page', 2);
        $response->assertJsonFragment([
            'total' => 20,
            'current_page' => 2,
        ]);
    }

    public static function dataProviderPagination(): array
    {
        return [
            'test paginate empty' => ['total' => 0, 'page' => 1, 'totalItemsPage' => 0],
            'test total 40 users page one' => ['total' => 40, 'page' => 1, 'totalItemsPage' => 15],
            'test total 20 users page two' => ['total' => 20, 'page' => 2, 'totalItemsPage' => 5],
            'test total 10 users page two' => ['total' => 10, 'page' => 2, 'totalItemsPage' => 0]
        ];
    }

    #[DataProvider('dataProviderUser')]
    public function test_create(array $data, int $statusCode, array $structureResponse)
    {
        $response = $this->postJson($this->endPoint, $data);
        $response->assertStatus($statusCode);
        $response->assertJsonStructure($structureResponse);
    }

    public static function dataProviderUser(): array
    {
        return [
            'test create user' => [
                'data' =>
                [
                    'name' => 'Luiz',
                    'email' => 'luiz.santos85@gmail.com',
                    'password' => '12345678'
                ],
                'statusCode' => Response::HTTP_CREATED,
                'structureResponse' =>
                [
                    'data' =>
                    [
                        'id',
                        'name',
                        'email',
                    ]
                ]
            ],
            'test validations' => [
                'data' => [],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'structureResponse' => [
                    'errors' => ['name']
                ]
            ],
        ];
    }

    public function test_show_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("{$this->endPoint}/{$user->email}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' =>
            [
                'id',
                'name',
                'email',
            ]
        ]);
    }

    public function test_show_user_not_found()
    {
        $response = $this->getJson("{$this->endPoint}/fake_value");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[DataProvider('dataProviderUpdateUser')]
    public function test_update_user(array $data, int $statusCode)
    {
        $user = User::factory()->create();
        $response = $this->putJson("{$this->endPoint}/{$user->email}", $data);

        $response->assertStatus($statusCode);
    }

    // public function test_update_user_validatons()
    // {
    //     $user = User::factory()->create();
    //     $response = $this->putJson("{$this->endPoint}/{$user->email}", [
    //         'name' => '',
    //         'password' => '123'
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonStructure([
    //         'errors' => ['name', 'password']
    //     ]);
    // }

    public static function dataProviderUpdateUser(): array
    {
        return [
            'test update user' => [
                'data' => [
                    'name' => 'Luiz Santos',
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_OK,
            ],
            'test validations - empty name' => [
                'data' => [
                    'name' => '',
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'test validations - short password' => [
                'data' => [
                    'name' => 'Nome Valido',
                    'password' => '123'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'test validations - name too long' => [
                'data' => [
                    'name' => str_repeat('a', 256),
                    'password' => 'new_password'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
            'test validations - missing fields' => [
                'data' => [],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ],
        ];
    }
}
