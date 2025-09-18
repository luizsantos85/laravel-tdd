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
    //Create the model to be tested for don't repeat code (new User) at each test
    protected function model(): Model
    {
        return new User();
    }

    //Test of traits
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

    //Test of fillable
    public function test_has_fillable(): void
    {
        $this->assertEquals(
            [
                'name',
                'email',
                'password',
            ],
            $this->model()->getFillable()
        );
    }

    //Test of incrementing
    public function test_incremementing_is_false()
    {
        $this->assertFalse($this->model()->incrementing);
    }

    //Test of casts
    public function test_has_casts(): void
    {
        $this->assertEquals(
            [
                'id' => 'string',
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ],
            $this->model()->getCasts()
        );
    }
}
