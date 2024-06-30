<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\ValueObjects\UUID;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'uuid' => UUID::generate()->__toString(),
            'user_id' => User::factory(),
            'balance' => 0,
        ];
    }
}
