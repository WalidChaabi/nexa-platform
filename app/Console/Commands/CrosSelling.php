<?php

namespace App\Console\Commands;

use App\Services\CampaignService;
use App\Services\CrossProductService;
use App\Services\InvoiceService;
use App\Services\ProductService;
use App\Services\SmsCrossSellingService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CrosSelling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cross-selling:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send SMS to All customers that consumed a specifique product after 20 days for example';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $productService;
    protected $invoiceService;
    protected $crossProductService;
    protected $smsCrossSellingService;
    protected $campaignService;
    public function __construct()
    {
        parent::__construct();
        $this->productService = new ProductService;
        $this->invoiceService = new InvoiceService;
        $this->crossProductService = new CrossProductService;
        $this->smsCrossSellingService = new SmsCrossSellingService;
        $this->campaignService = new CampaignService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO:get all the products apply the cross selling 
        $products =  $this->crossProductService->all();
        $customers =  $this->invoiceService->getCrossSelling($products)->filter();
        if (!empty($customers)) {
            $customers->map(function ($entities) {
                $entities->map(function ($entity) {
                    if (!empty($entity)) {
                        $this->smsTo($entity['customer']->phone, $entity['customer']->store->sender_id, $entity['message']);
                        $this->smsCrossSellingService->create([
                            'customer_id' => $entity['customer']->id,
                            'store_id' =>  $entity['customer']->store->id,
                            'cross_product_id' => $entity['cross_product_id'],
                            'sent_date' => Carbon::now(),
                            'credit' => 2,
                        ]);
                    }
                });
            });
        }
    }

    private function smsTo($phone, $senderid, $message)
    {
        $this->campaignService->sendSms($phone, $message, $senderid);
    }
}
