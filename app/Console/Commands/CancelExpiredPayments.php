<?php

namespace App\Console\Commands;

use App\Services\PaymentService;
use Illuminate\Console\Command;

class CancelExpiredPayments extends Command
{
    protected $signature = 'payments:cancel-expired';

    protected $description = 'Batalkan pembayaran pending yang sudah melewati batas expired dan kembalikan stok';

    public function handle(PaymentService $paymentService): int
    {
        $count = $paymentService->cancelExpiredPayments();

        $this->info("{$count} pembayaran pending dibatalkan.");

        return self::SUCCESS;
    }
}
