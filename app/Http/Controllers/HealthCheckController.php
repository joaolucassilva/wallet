<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            DB::connection()->getPdo();

            Redis::connection()->client()->ping();

            return response()->json(['message' => 'Application up']);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }
}
