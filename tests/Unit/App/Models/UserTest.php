<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * Class UserTest
 *
 * Test suite for the App\Models\User model. This class provides a set of
 * helper methods required by the parent ModelTestCase to verify common Eloquent
 * model properties, such as implemented traits, fillable attributes and casts.
 *
 * Each method returns the expected configuration so the shared assertions in
 * ModelTestCase can validate the model contract. Keep these expectations in
 * sync with the actual `User` model implementation.
 */
class UserTest extends ModelTestCase
{
    /**
     * Return an instance of the model under test.
     *
     * The parent `ModelTestCase` will use this instance to run generic
     * assertions (e.g. existence, table name, primary key behavior).
     *
     * @return Model
     */
    protected function model(): Model
    {
        return new User();
    }

    /**
     * Expected traits used by the User model.
     *
     * The test asserts that the model uses these traits so the application
     * behavior (API tokens, factories and notifications) stays available.
     *
     * @return array<string>
     */
    protected function traits(): array
    {
        return [
            // Provides API token management used by Sanctum
            HasApiTokens::class,
            // Enables model factories for tests
            HasFactory::class,
            // Sends notifications (mail, database, etc.)
            Notifiable::class,
        ];
    }

    /**
     * Expected fillable attributes for mass assignment.
     *
     * The parent test will confirm the model declares these attributes
     * in the `$fillable` array to prevent mass-assignment issues.
     *
     * @return array<string>
     */
    protected function fillable(): array
    {
        return [
            'name',
            'email',
            'password',
        ];
    }

    /**
     * Expected attribute casts configured on the User model.
     *
     * The parent test validates that Eloquent casts are configured
     * (for example `email_verified_at` as datetime and `password` as hashed).
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
