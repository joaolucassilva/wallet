<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\UserEntity;
use App\Domain\Enums\UserTypeEnum;
use App\Domain\ValueObjects\UUID;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    public function test_user_entity_when_legal_person_not_can_initiate_transfer(): void
    {
        $user = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'name test',
            email: 'test@test.com',
            phone: '1232887465243',
            password: 'asdasdad',
            type: UserTypeEnum::LEGAL_PERSON,
            document: '87654366500',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $this->assertFalse($user->canInitiateTransfer());
        $this->assertSame(UserTypeEnum::LEGAL_PERSON, $user->getType());
        $this->assertSame('test@test.com', $user->getEmail());
        $this->assertSame('1232887465243', $user->getPhone());
    }

    public function test_user_entity_when_natural_person_can_initiate_transfer(): void
    {
        $user = new UserEntity(
            id: 1,
            uuid: UUID::generate(),
            name: 'name test',
            email: 'test@test.com',
            phone: '1232887465243',
            password: 'asdasdad',
            type: UserTypeEnum::NATURAL_PERSON,
            document: '87654366500',
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        $this->assertTrue($user->canInitiateTransfer());
        $this->assertSame(UserTypeEnum::NATURAL_PERSON, $user->getType());
        $this->assertSame('test@test.com', $user->getEmail());
        $this->assertSame('1232887465243', $user->getPhone());
    }
}
