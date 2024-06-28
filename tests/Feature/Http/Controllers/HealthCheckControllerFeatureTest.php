<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class HealthCheckControllerFeatureTest extends TestCase
{
    public function test_application_health_should_return_application_up(): void
    {
        $this->getJson(route('v1.health'))
            ->assertOk()
            ->assertExactJson([
                'message' => 'Application up',
            ]);
    }

    public function test_should_return_status_error_when_check_database_failure(): void
    {
        DB::shouldReceive('connection->getPdo')
            ->andThrow(new Exception('Database connection error'));

        $this->getJson(route('v1.health'))
            ->assertInternalServerError()
            ->assertExactJson([
                'status' => 'error',
            ]);
    }

    public function test_should_return_status_error_when_check_redis_failure(): void
    {
        Redis::shouldReceive('connection->client->ping')
            ->andThrow(new Exception('Redis connection error'));

        $this->getJson(route('v1.health'))
            ->assertInternalServerError()
            ->assertExactJson([
                'status' => 'error',
            ]);
    }
}
