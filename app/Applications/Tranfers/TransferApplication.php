<?php

declare(strict_types=1);

namespace App\Applications\Tranfers;

use App\Domain\Database\Repositories\TransferRepositoryInterface;
use App\Domain\Database\Repositories\WalletRepositoryInterface;
use App\Domain\Database\UnitOfWorkInterface;
use App\Domain\Entities\TransferEntity;
use App\Domain\Events\PaymentProcessed;
use App\Domain\Exceptions\InsufficientBalanceException;
use App\Domain\Exceptions\PaymentAuthorizeException;
use App\Domain\Exceptions\UserDoesNotHavePermissionException;
use App\Domain\Gateways\PaymentAuthorizationGatewayInterface;
use Illuminate\Support\Facades\Event;

readonly class TransferApplication
{
    public function __construct(
        private UnitOfWorkInterface $unitOfWork,
        private WalletRepositoryInterface $walletRepository,
        private TransferRepositoryInterface $transferRepository,
        private PaymentAuthorizationGatewayInterface $paymentAuthorization,
    ) {
    }

    /**
     * @param InputDTO $input
     * @return void
     */
    public function execute(InputDTO $input): void
    {
        $this->unitOfWork->beginTransaction();

        try {
            $walletPayer = $this->walletRepository->findByUserId($input->payer);
            $walletPayee = $this->walletRepository->findByUserId($input->payee);

            $walletPayer->debit($input->amount);
            $walletPayee->credit($input->amount);

            if (!$this->paymentAuthorization->authorize()) {
                throw new PaymentAuthorizeException();
            }

            $this->walletRepository->updateBalance($walletPayer);
            $this->walletRepository->updateBalance($walletPayee);

            $this->transferRepository
                ->create(
                    TransferEntity::createIncoming(
                        payer: $walletPayer,
                        payee: $walletPayee,
                        amount: $input->amount,
                    )
                );

            $this->transferRepository
                ->create(
                    TransferEntity::createOutgoing(
                        payer: $walletPayer,
                        payee: $walletPayee,
                        amount: $input->amount,
                    )
                );

            $this->unitOfWork->commit();

            Event::dispatch(new PaymentProcessed($walletPayee));
        } catch (PaymentAuthorizeException | InsufficientBalanceException | UserDoesNotHavePermissionException $e) {
            $this->unitOfWork->rollBack();

            report($e);
        }
    }
}
