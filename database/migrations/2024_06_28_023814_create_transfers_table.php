<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('transfers', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('payer_wallet_id')->references('id')->on('wallets');
            $table->foreignId('payee_wallet_id')->references('id')->on('wallets');
            $table->integer('amount');
            $table->enum('type', ['incoming', 'outgoing']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
