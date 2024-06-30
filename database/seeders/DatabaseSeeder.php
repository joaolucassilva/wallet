<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Enums\UserTypeEnum;
use App\Domain\ValueObjects\UUID;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->state(
                new Sequence(
                    [
                        'id' => 1,
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'usuario pf 1',
                        'email' => 'usuario1@mail.com',
                        'phone' => '55119912173334',
                        'password' => Hash::make('password'),
                        'document' => '12309876533',
                        'type' => UserTypeEnum::NATURAL_PERSON->value,
                    ],
                    [
                        'id' => 2,
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'usuario pf 2',
                        'email' => 'usuario2@mail.com',
                        'phone' => '55223312177734',
                        'password' => Hash::make('password'),
                        'document' => '22209899933',
                        'type' => UserTypeEnum::NATURAL_PERSON->value,
                    ],
                    [
                        'id' => 3,
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'usuario lojista',
                        'email' => 'lojista1@mail.com',
                        'phone' => '55211117172239',
                        'password' => Hash::make('password'),
                        'document' => '61158739000110',
                        'type' => UserTypeEnum::LEGAL_PERSON->value,
                    ],
                )
            )
            ->create();

        Wallet::factory()
            ->count(3)
            ->state(
                new Sequence(
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => 1,
                        'balance' => 100000,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => 2,
                        'balance' => 100000,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => 3,
                        'balance' => 0,
                    ],
                )
            )
            ->create();
    }
}
