<?php

namespace App\Integrations\Payment;

use App\Contracts\Integrations\PaymentGateway;

class CmiPaymentGateway implements PaymentGateway
{
    public function __construct(private readonly CmiHashVerifier $hashVerifier) {}

    public function initiate(PaymentInitiationRequest $request): PaymentRedirect
    {
        $fields = [
            'clientid' => config('integrations.cmi.client_id'),
            'storetype' => '3d_pay_hosting',
            'hashAlgorithm' => 'ver3',
            'trantype' => 'PreAuth',
            'amount' => number_format($request->amount, 2, '.', ''),
            'currency' => config('integrations.cmi.currency', '504'),
            'oid' => $request->orderId,
            'okUrl' => $request->successUrl,
            'failUrl' => $request->failureUrl,
            'callbackUrl' => $request->callbackUrl,
            'shopurl' => $request->shopUrl,
            'email' => $request->email,
            'BillToName' => $request->customerName,
            'BillToCompany' => $request->companyName,
            'BillToStreet1' => $request->address,
            'tel' => $request->phone,
            'lang' => $request->language,
            'AutoRedirect' => 'true',
            'CallbackResponse' => 'true',
        ];

        $storeKey = (string) config('integrations.cmi.store_key');
        $fields['hash'] = $this->hashVerifier->calculateHash($fields, $storeKey);

        return new PaymentRedirect(
            actionUrl: config('integrations.cmi.gateway_url'),
            fields: array_map(fn ($value) => (string) $value, $fields),
        );
    }

    public function verifyCallback(array $payload): PaymentVerification
    {
        if (! isset($payload['HASH']) && ! isset($payload['hash'])) {
            return new PaymentVerification(
                status: PaymentVerificationStatus::Incomplete,
                hashValid: false,
                gatewayResponse: 'request incomplete',
            );
        }

        $storeKey = (string) config('integrations.cmi.store_key');
        $hashValid = $this->hashVerifier->matches($payload, $storeKey);

        if (! $hashValid) {
            return new PaymentVerification(
                status: PaymentVerificationStatus::HashMismatch,
                hashValid: false,
                procReturnCode: isset($payload['ProcReturnCode']) ? (string) $payload['ProcReturnCode'] : null,
                gatewayResponse: 'FAILURE',
            );
        }

        $procReturnCode = isset($payload['ProcReturnCode']) ? (string) $payload['ProcReturnCode'] : null;

        if ($procReturnCode === '00') {
            return new PaymentVerification(
                status: PaymentVerificationStatus::PostAuth,
                hashValid: true,
                procReturnCode: $procReturnCode,
                gatewayResponse: 'ACTION=POSTAUTH',
            );
        }

        return new PaymentVerification(
            status: PaymentVerificationStatus::Approved,
            hashValid: true,
            procReturnCode: $procReturnCode,
            gatewayResponse: 'APPROVED',
        );
    }
}
