<?php

namespace Tests\Unit\App\Models;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillable(): array;
    abstract protected function casts(): array;


    //Test of traits
    public function test_traits(): void
    {
        $model = array_keys(class_uses($this->model()));

        $this->assertEquals(
            $this->traits(),
            array_values($model)
        );
    }

    //Test of fillable
    public function test_has_fillable(): void
    {
        $this->assertEquals(
            $this->fillable(),
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
            $this->casts(),
            $this->model()->getCasts()
        );
    }
}
