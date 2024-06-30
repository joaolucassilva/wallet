<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'payer_wallet_id',
        'payee_wallet_id',
        'amount',
        'type',
        'created_at',
        'updated_at',
    ];
}
