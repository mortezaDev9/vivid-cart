<?php

declare(strict_types=1);

namespace Modules\Payment\Gateways;

use Illuminate\Http\RedirectResponse;

interface PaymentGatewayInterface
{
    public function request(int $amount, string $callback, string $description, ?array  $data): array;

    public function pay(string $authority): RedirectResponse;

    public function verify(int $amount, string $authority): array;
}
