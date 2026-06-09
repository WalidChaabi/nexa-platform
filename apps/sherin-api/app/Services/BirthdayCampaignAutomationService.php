<?php

namespace App\Services;

use App\Models\CampaignDetail;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Message;
use App\Models\MessageCustomer;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BirthdayCampaignAutomationService
{
    public function __construct(
        private readonly CampaignFilterService $filterService,
    ) {}

    /**
     * @return array{campaigns_processed: int, messages_created: int, skipped: int}
     */
    public function runDaily(?Carbon $referenceDate = null): array
    {
        $reference = ($referenceDate ?? now())->copy();
        $campaignsProcessed = 0;
        $messagesCreated = 0;
        $skipped = 0;

        $details = CampaignDetail::query()
            ->where('active', 1)
            ->whereHas('campaign', fn ($query) => $query->where('type', 'birthday'))
            ->with([
                'campaign',
                'filter',
                'store',
                'messageCampaigns.message',
            ])
            ->get();

        foreach ($details as $detail) {
            $campaignsProcessed++;
            $filter = $detail->filter;
            $store = $detail->store;

            if (! $filter || ! $store) {
                $skipped++;

                continue;
            }

            foreach ($detail->messageCampaigns as $messageCampaign) {
                $template = $messageCampaign->message;

                if (! $template instanceof Message) {
                    $skipped++;

                    continue;
                }

                $customers = $this->filterService
                    ->birthdayAudienceQuery(
                        $filter,
                        (int) $store->id,
                        $reference,
                        (int) $template->date_start,
                    )
                    ->get();

                foreach ($customers as $customer) {
                    if ($this->alreadyQueuedToday($customer, (int) $store->id, $reference)) {
                        $skipped++;

                        continue;
                    }

                    $code = $this->resolvePromoCode($customer);
                    $body = $this->personalizeMessage($template->message, $customer, $code);

                    MessageCustomer::query()->create([
                        'store_id' => $store->id,
                        'customer_id' => $customer->id,
                        'message' => $body,
                        'type' => (string) $detail->campaign?->type,
                        'payed' => 0,
                        'status' => 0,
                    ]);

                    $messagesCreated++;

                    IntegrationLogger::info('scheduler.birthday_campaign.queued', [
                        'store_id' => $store->id,
                        'customer_id' => $customer->id,
                        'phone_masked' => IntegrationLogger::maskPhone((string) $customer->phone),
                    ]);
                }
            }
        }

        return [
            'campaigns_processed' => $campaignsProcessed,
            'messages_created' => $messagesCreated,
            'skipped' => $skipped,
        ];
    }

    private function alreadyQueuedToday(
        Customer $customer,
        int $storeId,
        Carbon $reference,
    ): bool {
        return MessageCustomer::query()
            ->where('store_id', $storeId)
            ->where('customer_id', $customer->id)
            ->where('type', 'birthday')
            ->where('payed', 0)
            ->whereDate('created_at', $reference->toDateString())
            ->exists();
    }

    private function resolvePromoCode(Customer $customer): string
    {
        $existing = Code::query()
            ->where('customer_id', $customer->id)
            ->whereNotNull('code_red')
            ->first();

        if ($existing) {
            return (string) $existing->code_red;
        }

        $codeRed = $this->generateCode();

        Code::query()->create([
            'customer_id' => $customer->id,
            'code_red' => $codeRed,
            'status' => '0',
        ]);

        return $codeRed;
    }

    private function generateCode(int $length = 6): string
    {
        return Str::upper(Str::random($length));
    }

    private function personalizeMessage(
        string $template,
        Customer $customer,
        string $code,
    ): string {
        return str_replace(
            ['[FirstName]', '[LastName]', '[code]'],
            [
                (string) ($customer->firstname ?? ''),
                (string) ($customer->lastname ?? ''),
                $code,
            ],
            $template,
        );
    }
}
