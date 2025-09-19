<?php

namespace Tests\Unit\App\Models;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;

/**
 * Base test case for Eloquent models used in unit tests.
 *
 * This abstract PHPUnit TestCase provides a collection of reusable tests
 * that verify common model configuration aspects. Concrete test classes
 * should extend this class and supply the model under test along with
 * the expected traits, fillable attributes and casts.
 *
 * Responsibilities of subclasses:
 * - model(): Model
 *     Return an instance of the Eloquent model to be tested.
 *
 * - traits(): array
 *     Return an ordered array of fully-qualified trait class names that
 *     the model is expected to use.
 *
 * - fillable(): array
 *     Return an ordered array of attribute names that should be present
 *     on the model's $fillable property.
 *
 * - casts(): array
 *     Return an associative array of attribute casts expected on the model.
 *
 * Provided tests:
 * - test_traits()
 *     Asserts that the model uses exactly the traits provided by traits().
 *
 * - test_has_fillable()
 *     Asserts that the model's fillable attributes match the result of
 *     fillable().
 *
 * - test_incremementing_is_false()
 *     Asserts that the model's $incrementing property is false.
 *
 * - test_has_casts()
 *     Asserts that the model's casts match the result of casts().
 *
 * Implementation notes:
 * - The tests compare arrays for equality and are therefore order-sensitive.
 * - Return deterministic arrays from the abstract methods to ensure stable tests.
 *
 * @package Tests\Unit\App\Models
 */
abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillable(): array;
    abstract protected function casts(): array;


    /**
     * Verify that the model uses the expected traits.
     *
     * Notes:
     * - The comparison is order-sensitive: `traits()` must return a
     *   deterministic array in the correct order.
     * - We use `class_uses()` to inspect traits used by the concrete
     *   model instance and compare their keys/values with the expectation.
     *
     * @return void
     */
    public function test_traits(): void
    {
        // class_uses returns an array of trait FQCNs indexed by trait name.
        $modelTraits = array_keys(class_uses($this->model()));

        // Compare expected traits (from the test) with actual traits.
        $this->assertEquals(
            $this->traits(),
            array_values($modelTraits)
        );
    }

    /**
     * Ensure the model's $fillable attributes match the expectation.
     *
     * This prevents accidental mass-assignment exposure and documents
     * which attributes are intended to be mass assignable.
     *
     * @return void
     */
    public function test_has_fillable(): void
    {
        $this->assertEquals(
            $this->fillable(),
            $this->model()->getFillable()
        );
    }

    /**
     * Assert that the model's $incrementing property is false.
     *
     * Many modern applications use non-incrementing primary keys
     * (UUIDs / strings). This test documents that expectation and will
     * fail if the model uses auto-increment integers.
     *
     * @return void
     */
    public function test_incremementing_is_false()
    {
        $this->assertFalse($this->model()->incrementing);
    }

    /**
     * Verify the attribute casts defined on the model.
     *
     * The test compares the model's `getCasts()` with the expected
     * associative array from `casts()`. The comparison is order-sensitive
     * for deterministic results; prefer returning a stable array.
     *
     * @return void
     */
    public function test_has_casts(): void
    {
        $this->assertEquals(
            $this->casts(),
            $this->model()->getCasts()
        );
    }
}
