<?php

declare(strict_types=1);

namespace App\Adapters\Gateways;

use App\Adapters\Clients\PaymentAuthorizationClient;
use App\Domain\Gateways\PaymentAuthorizationGatewayInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class PaymentAuthorizationGateway implements PaymentAuthorizationGatewayInterface
{
    private const string AUTHORIZATION_STATUS = 'success';

    public function __construct(
        private readonly PaymentAuthorizationClient $authorizationClient,
    ) {
    }

    public function authorize(): bool
    {
        try {
            $response = $this->authorizationClient
                ->getClient()
                ->get(
                    uri: config('services.payment_authorization.url'),
                )
                ->getBody()
                ->getContents();

            $response = json_decode(
                $response,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            return $response['status'] === self::AUTHORIZATION_STATUS && $response['data']['authorization'];
        } catch (GuzzleException | Exception $e) {
            report($e);

            return false;
        }
    }
}
