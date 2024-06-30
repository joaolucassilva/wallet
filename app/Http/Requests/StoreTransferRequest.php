<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Applications\Tranfers\InputDTO;
use App\Domain\ValueObjects\Money;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'payer' => 'required',
            'payee' => 'required',
            'value' => 'required',
        ];
    }

    public function toDTO(): InputDTO
    {
        return new InputDTO(
            payer: $this->payer,
            payee: $this->payee,
            amount: Money::setAmountDecimal($this->value),
        );
    }
}
