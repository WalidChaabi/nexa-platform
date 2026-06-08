<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Customer;
use App\Models\MessageCustomer;
use App\models\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MessageService
{
    protected $campaignService;

    public function __construct()
    {
        $this->campaignService = new CampaignService;
    }
    public function filterCustomer($filter, $store_id,$customer_id,$type)
    {
        $invoices =  Customer::join('invoices','invoices.customer_id', '=', 'customers.id')->where('customers.id',$customer_id)->select('invoices.id')->get();

        if($filter->active == 1){
            if (count($invoices) === 0) {
                return $customer =  Customer::where([
                    ['customers.points', '<=', $filter['points']],
                    ['customers.store_id', '=', $store_id],
                ])
                ->where(function ($query) use ($filter) {
                    if ($filter['sexe'] == 0) {
                        $query->where('customers.sexe', '=', 0);
                    } elseif ($filter['sexe'] == 1) {
                        $query->where('customers.sexe', '=', 1);
                    } elseif ($filter['sexe'] == 2) {
                        $query->where(function ($query) {
                            $query->where('customers.sexe', '=', 0)
                                  ->orWhere('customers.sexe', '=', 1);
                        });
                    }
                })
                ->where(function ($query) use ($type, $customer_id) {
                    if ($type == 'bienvenu' || $type == 'rdv') {
                        $query->where('customers.id', $customer_id);
                    }
                })
                ->whereBetween('customers.birth', [Carbon::now()->subYears($filter['age_max']), Carbon::now()->subYears($filter['age_min'])])
                ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
                ->distinct('customers.id')
                ->get();            
            } else {
                return $customer =  Customer::where([
                    ['customers.points', '<=', $filter['points']],
                    ['customers.store_id', '=', $store_id],
                ])
                ->where(function ($query) use ($filter) {
                    if ($filter['sexe'] == 0) {
                        $query->where('customers.sexe', '=', 0);
                    } elseif ($filter['sexe'] == 1) {
                        $query->where('customers.sexe', '=', 1);
                    } elseif ($filter['sexe'] == 2) {
                        $query->where(function ($query) {
                            $query->where('customers.sexe', '=', 0)
                                  ->orWhere('customers.sexe', '=', 1);
                        });
                    }
                })
                ->where(function ($query) use ($type, $customer_id) {
                    if ($type == 'bienvenu' || $type == 'rdv') {
                        $query->where('customers.id', $customer_id);
                    }
                })
                ->leftJoin('invoices', function($join,$filter) {
                    $join->on('invoices.customer_id', '=', 'customers.id')
                         ->whereBetween('invoices.invoice_date', [Carbon::now()->subMonth($filter['last_visit']), Carbon::now()])
                         ->whereRaw('TIME(invoices.invoice_date) BETWEEN ? AND ?', [
                            Carbon::parse($filter['heure_start'])->format('H:i:s'),
                            Carbon::parse($filter['heure_end'])->format('H:i:s'),
                        ]);
                })
                ->where(function ($query) {
                    $query->whereNull('invoices.customer_id');
                })
                ->whereBetween('customers.birth', [Carbon::now()->subYears($filter['age_max']), Carbon::now()->subYears($filter['age_min'])])
                ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
                ->distinct('customers.id')
                ->get();            
            }

        }
        else{
            return $customer =  Customer::where([
                ['points', '<=', $filter['points']],
                ['store_id', '=', $store_id],
                ['id', '=', $customer_id],
            ])
            ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
            ->distinct('customers.id')
            ->get();
        }
    }
    // public function filterCustomer($filter, $store_id,$customer_id){
    //     $customer = Customer::leftJoin('invoices', 'invoices.customer_id', '=', 'customers.id')
    //         ->where([
    //             ['customers.points', '<=', $filter['points']],
    //             ['customers.store_id', '=', $store_id],
    //             ['customers.id', '=', $customer_id],
    //         ])
    //         ->where(function ($query) use ($filter) {
    //             if ($filter['sexe'] == 0) {
    //                 $query->where('customers.sexe', '=', 0);
    //             } elseif ($filter['sexe'] == 1) {
    //                 $query->where('customers.sexe', '=', 1);
    //             } elseif ($filter['sexe'] == 2) {
    //                 $query->where('customers.sexe', '=', 0)
    //                     ->orWhere('customers.sexe', '=', 1);
    //             }
    //         })
    //         // ->where(function ($query) use ($type,$rdv_id,$cross_id) {
    //         //     $now = Carbon::now()->format('y-m-d H:i:s');
    //         //     $old = Carbon::now()->subMinutes(1)->format('y-m-d H:i:s');
    //         //     if ($type == 'bienvenu') {
    //         //         $query->whereBetween('invoices.invoice_date', [$old, $now]);
    //         //     } 
    //         //     elseif ($type == 'rdv') {
    //         //         $query->where('customer_id', $rdv_id);
    //         //     }
    //         //     elseif ($type == 'cross-selling') {
    //         //         $query->where('customer_id', $cross_id);
    //         //     }
    //         // })
    //         ->where(function ($query) use ($filter) {
    //             $query->whereBetween('invoices.invoice_date', [Carbon::now()->subMonth($filter['last_visit']), Carbon::now()])
    //                 ->orWhereNull('invoices.invoice_date');
    //         })
    //         ->whereRaw('TIME(IFNULL(invoices.invoice_date, NOW())) BETWEEN ? AND ?', [
    //             Carbon::parse($filter['heure_start'])->format('H:i:s'),
    //             Carbon::parse($filter['heure_end'])->format('H:i:s'),
    //         ])
    //         ->whereNotNull('customers.birth')
    //         ->whereBetween('customers.birth', [Carbon::now()->subYears($filter['age_max']), Carbon::now()->subYears($filter['age_min'])])
    //         ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
    //         ->distinct('customers.id')
    //         ->get();
    //     // dd($customer);
    //     return $customer;
    // }
    public function MessageDetails($store_id, $customer, $CampaignDetail, $message, $date)
    {
        $smsDetails = [];
        $sender_id = Store::where('id', '=', $store_id)->value('name');
        $smsDetails[$customer->firstname]['id'] = $customer->id;
        $smsDetails[$customer->firstname]['phone'] = $customer->phone;
        $smsDetails[$customer->firstname]['sender_id'] = $sender_id;


        $code_red = 0;
        $code = Code::where('customer_id', $customer->id)
            ->whereNotNull('code_red')
            ->first();
        if (is_null($code)) {
            $code_red = $this->campaignService->generateCode(6);
            $code = Code::create([
                'customer_id' => $customer->id,
                'code_red' => $code_red,
                'status' => 0,
            ]);
            $smsDetails[$customer->firstname]['code'] = $code_red;
        } else {
            $code_red = $code->code_red;
            $smsDetails[$customer->firstname]['code'] = $code_red;
        }
        $type =$CampaignDetail['type'];
        if($type == 'rdv'){
            $messageCustomer = $this->campaignService->MessageRdv($message->message, $customer->firstname, $customer->lastname, $code_red,$date);
        }else{
            $messageCustomer = $this->campaignService->Message($message->message, $customer->firstname, $customer->lastname, $code_red);
        }
        $smsDetails[$customer->firstname]['messages'][] = $messageCustomer;

        $msg = MessageCustomer::create([
            'store_id' => $store_id,
            'customer_id' => $customer->id,
            'message' => $messageCustomer,
            'type' => $CampaignDetail['type'],
        ]);
        $smsDetails[$customer->firstname]['type'] = $CampaignDetail['type'];
        return $smsDetails;
    }
}
