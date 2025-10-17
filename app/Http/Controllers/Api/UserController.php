<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateUserFormRequest;
use App\Http\Requests\User\createUserFormRequest;
use App\Http\Resources\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Exception\NotFoundException;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        // $users = collect($this->repository->findAll());
        $response = $this->repository->paginate();
        return UserResource::collection(collect($response->items()))
        // Obs: Pode-se trabalhar com outro Presenter separado só para retorno do JSON
            ->additional([
                'meta' => [
                    'total' => $response->total(),
                    'current_page' => $response->currentPage(),
                    'first_page' => $response->firstPage(),
                    'last_page' => $response->lastPage(),
                    'per_page' => $response->perPage(),
                ]
            ]);
    }

    public function store(createUserFormRequest $request)
    {
        $data = $request->validated();

        $user = $this->repository->create($data);

        return new UserResource($user);
    }

    public function show(string $email)
    {
        try {
            $user = $this->repository->findByEmail($email);
            return new UserResource($user);
        } catch (NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(updateUserFormRequest $request, $email)
    {
        $data = $request->validated();

        $user = $this->repository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->repository->update($email, $data);

        return new UserResource($this->repository->findByEmail($email));
    }

    // public function destroy(string $email)
    // {
    //     $user = $this->repository->findByEmail($email);

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     $this->repository->delete($email);

    //     return response()->json([], 204);
    // }

}
