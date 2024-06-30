<?php

declare(strict_types=1);

namespace Tests\Feature\Applications\Tranfers;

use App\Adapters\Clients\PaymentAuthorizationClient;
use App\Adapters\Database\Repositories\EloquentORM\TransferRepository;
use App\Adapters\Database\Repositories\EloquentORM\WalletRepository;
use App\Adapters\Gateways\PaymentAuthorizationGateway;
use App\Applications\Tranfers\InputDTO;
use App\Applications\Tranfers\TransferApplication;
use App\Domain\Database\UnitOfWorkInterface;
use App\Domain\Enums\UserTypeEnum;
use App\Domain\Events\PaymentProcessed;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\UUID;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class TransferApplicationFeatureTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_transfer_with_success(): void
    {
        Event::fake();

        [$userPayer, $userPayee] = User::factory()
            ->count(2)
            ->state(
                new Sequence(
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'name test 1',
                        'email' => 'name.test1@example.com',
                        'phone' => '2233009455',
                        'password' => Hash::make('password'),
                        'document' => '12312312300',
                        'type' => UserTypeEnum::NATURAL_PERSON,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'name test 2',
                        'email' => 'name.test2@example.com',
                        'phone' => '2233222111',
                        'password' => Hash::make('password'),
                        'document' => '11122212311',
                        'type' => UserTypeEnum::NATURAL_PERSON,
                    ],
                )
            )
            ->create();

        [$payerWallet, $payeeWallet] = Wallet::factory()
            ->count(2)
            ->state(
                new Sequence(
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => $userPayer->id,
                        'balance' => 100000,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => $userPayee->id,
                        'balance' => 100000,
                    ],
                )
            )
            ->create();

        $clientMock = Mockery::mock(PaymentAuthorizationClient::class);
        $clientMock
            ->shouldReceive('getClient->get->getBody->getContents')
            ->once()
            ->andReturn(
                json_encode([
                    'status' => 'success',
                    'data' => [
                        'authorization' => true,
                    ],
                ], JSON_THROW_ON_ERROR)
            );

        $paymentAuthorization = new PaymentAuthorizationGateway($clientMock);
        $application = new TransferApplication(
            unitOfWork: app()->make(UnitOfWorkInterface::class),
            walletRepository: new WalletRepository(new Wallet()),
            transferRepository: new TransferRepository(new Transfer()),
            paymentAuthorization: $paymentAuthorization
        );

        $application->execute(InputDTO::from([
            'payer' => $userPayer->id,
            'payee' => $userPayee->id,
            'amount' => new Money(100000),
        ]));

        $payerWallet->refresh();
        $payeeWallet->refresh();

        $this->assertSame(0, $payerWallet->balance);
        $this->assertSame(200000, $payeeWallet->balance);
        $this->assertDatabaseCount(Transfer::class, 2);

        Event::dispatch(PaymentProcessed::class);
    }

    public function test_execute_transfer_when_payment_not_authorization_should_uncommited_transactions(): void
    {
        Event::fake();

        [$userPayer, $userPayee] = User::factory()
            ->count(2)
            ->state(
                new Sequence(
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'name test 1',
                        'email' => 'name.test1@example.com',
                        'phone' => '2233009455',
                        'password' => Hash::make('password'),
                        'document' => '12312312300',
                        'type' => UserTypeEnum::NATURAL_PERSON,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'name' => 'name test 2',
                        'email' => 'name.test2@example.com',
                        'phone' => '2233222111',
                        'password' => Hash::make('password'),
                        'document' => '11122212311',
                        'type' => UserTypeEnum::NATURAL_PERSON,
                    ],
                )
            )
            ->create();

        [$payerWallet, $payeeWallet] = Wallet::factory()
            ->count(2)
            ->state(
                new Sequence(
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => $userPayer->id,
                        'balance' => 100000,
                    ],
                    [
                        'uuid' => UUID::generate()->__toString(),
                        'user_id' => $userPayee->id,
                        'balance' => 100000,
                    ],
                )
            )
            ->create();

        $clientMock = Mockery::mock(PaymentAuthorizationClient::class);
        $clientMock
            ->shouldReceive('getClient->get->getBody->getContents')
            ->once()
            ->andReturn(
                json_encode([
                    'status' => 'fail',
                    'data' => [
                        'authorization' => false,
                    ],
                ], JSON_THROW_ON_ERROR)
            );

        $paymentAuthorization = new PaymentAuthorizationGateway($clientMock);
        $application = new TransferApplication(
            unitOfWork: app()->make(UnitOfWorkInterface::class),
            walletRepository: new WalletRepository(new Wallet()),
            transferRepository: new TransferRepository(new Transfer()),
            paymentAuthorization: $paymentAuthorization
        );

        $application->execute(InputDTO::from([
            'payer' => $userPayer->id,
            'payee' => $userPayee->id,
            'amount' => new Money(100000),
        ]));

        $payerWallet->refresh();
        $payeeWallet->refresh();

        $this->assertSame(100000, $payerWallet->balance);
        $this->assertSame(100000, $payeeWallet->balance);
        $this->assertDatabaseCount(Transfer::class, 0);

        Event::assertNotDispatched(PaymentProcessed::class);
    }
}
