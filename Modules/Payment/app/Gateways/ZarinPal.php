<?php

declare(strict_types=1);

namespace Modules\Payment\Gateways;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

readonly class ZarinPal implements PaymentGatewayInterface
{
    private string $requestUrl;
    private string $verifyUrl;
    private string $startPayUrl;

    public function __construct(private string $merchantId)
    {
        $isSandbox = config('payment.zarinpal.sandbox');

        $this->requestUrl  = $isSandbox
            ? config('payment.zarinpal.urls.request.sandbox')
            : config('payment.zarinpal.urls.request.production');

        $this->verifyUrl   = $isSandbox
            ? config('payment.zarinpal.urls.verify.sandbox')
            : config('payment.zarinpal.urls.verify.production');

        $this->startPayUrl = $isSandbox
            ? config('payment.zarinpal.urls.start_pay.sandbox')
            : config('payment.zarinpal.urls.start_pay.production');
    }

    public function request(
        int    $amount,
        string $callback,
        string $description,
        ?array $data = [],
    ): array
    {
        $response = Http::post($this->requestUrl, [
            'merchant_id'  => $this->merchantId,
            'amount'       => $amount,
            'description'  => $description,
            'callback_url' => $callback,
            'metadata'     => $data,
        ]);

        return json_decode($response->body(), true);
    }

    public function pay(string $authority): RedirectResponse
    {
        return redirect($this->startPayUrl.$authority);
    }

    public function verify(int $amount, string $authority): array
    {
        $response = Http::post($this->verifyUrl, [
            'merchant_id' => $this->merchantId,
            'amount'      => $amount,
            'authority'   => $authority,
        ]);

        return json_decode($response->body(), true);
    }
}
