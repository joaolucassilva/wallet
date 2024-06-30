<?php

declare(strict_types=1);

namespace App\Domain\Gateways;

interface PaymentAuthorizationGatewayInterface
{
    public function authorize(): bool;
}
