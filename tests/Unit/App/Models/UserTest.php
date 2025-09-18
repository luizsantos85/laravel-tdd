<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class UserTest extends TestCase
{
    protected function model(): Model
    {
        return new User();
    }

    public function test_traits(): void
    {
        $model = array_keys(class_uses($this->model()));

        $expectedTraits = [
            HasApiTokens::class,
            HasFactory::class,
            Notifiable::class,
        ];

        $this->assertEquals(
            $expectedTraits,
            array_values($model)
        );
    }
}
