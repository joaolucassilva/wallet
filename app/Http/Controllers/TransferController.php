<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Jobs\ProcessTransfer;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    public function __invoke(StoreTransferRequest $request): JsonResponse
    {
        ProcessTransfer::dispatch($request->toDTO());

        return response()->json([
            'message' => 'Transferência em processamento',
        ]);
    }
}
