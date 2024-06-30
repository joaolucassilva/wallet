<?php

declare(strict_types=1);

use App\Applications\Tranfers\InputDTO;
use App\Domain\Entities\TransferEntity;
use App\Domain\Exceptions\PaymentAuthorizeException;
use App\Domain\Gateways\PaymentAuthorizationGatewayInterface;
use App\Domain\Repositories\TransferRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\WalletRepositoryInterface;
use App\Domain\UnitOfWorkInterface;

class TransferApplication
{
    public function __construct(
        private readonly UnitOfWorkInterface $unitOfWork,
        private readonly UserRepositoryInterface $userRepository,
        private readonly WalletRepositoryInterface $walletRepository,
        private readonly TransferRepositoryInterface $transferRepository,
        private readonly PaymentAuthorizationGatewayInterface $paymentAuthorizationGateway,
    ) {
    }

    public function execute(InputDTO $input): void
    {
        $this->unitOfWork->beginTransaction();

        try {
            $payer = $this->userRepository->findById($input->payer);
            if (!$payer->canInitiateTransfer()) {
                throw new Exception('The payer user does not have permission to initiate transfers.');
            }

            $payee = $this->userRepository->findById($input->payee);

            $payer->getWallet()?->debit($input->amount);
            $payee->getWallet()?->credit($input->amount);

            if ($this->paymentAuthorizationGateway->authorize()) {
                throw new PaymentAuthorizeException();
            }

            $this->walletRepository->updateBalance($payer->getWallet());
            $this->walletRepository->updateBalance($payee->getWallet());

            $this->transferRepository
                ->create(
                    TransferEntity::createIncoming(
                        payer: $payer->getWallet(),
                        payee: $payee->getWallet(),
                        amount: $input->amount,
                    )
                );

            $this->transferRepository
                ->create(
                    TransferEntity::createOutgoing(
                        payer: $payer->getWallet(),
                        payee: $payee->getWallet(),
                        amount: $input->amount,
                    )
                );

            $this->unitOfWork->commit();
        } catch (Exception $e) {
            $this->unitOfWork->rollBack();

            report($e);
        }
    }
}
