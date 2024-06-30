<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\UUID;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UUIDTest extends TestCase
{
    public function test_generate_uuid(): void
    {
        $uuid = UUID::generate();

        $this->assertInstanceOf(UUID::class, $uuid);
        $this->assertIsString($uuid->__toString());
    }

    public function test_invalid_uuid_should_throw_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid UUID');

        new UUID('123knas-asdnasd-asdnasd');
    }
}
