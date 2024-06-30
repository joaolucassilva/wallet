<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Enums\UserTypeEnum;
use App\Domain\ValueObjects\UUID;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'uuid' => UUID::generate()->__toString(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
            'type' => UserTypeEnum::LEGAL_PERSON->value,
            'document' => '12312312300',
        ];
    }
}
