<?php

use App\Http\Controllers\test;
use App\Http\Controllers\Web\OwnerController;
use App\Http\Controllers\Web\CacheController;
use App\Models\CampaignDetail;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\MessageCampaign;
use App\Models\MessageCustomer;
use App\models\Store;
use App\Services\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::get('/EnvoiUnique', function () {
//     Artisan::call('envoi:unique');    
//     $output = Artisan::output();
// });
// Route::get('/clear-route-cache', [CacheController::class,'clearRouteCache']);

// //clear config cache
// Route::get('/clear-config-cache', [CacheController::class,'clearConfigCache']);

// // clear application cache
// Route::get('/clear-app-cache', [CacheController::class,'clearAppCache']);

// // clear view cache
// Route::get('/clear-view-cache', [CacheController::class,'clearViewCache']);

// Route::get('/greeting', [test::class, 'test']);

// Route::get('/test', function () {
//     $data = collect();
//     $customersData = [];

//     $filters = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
//         ->where('campaigns.type', '=', 'birthday')
//         ->select('campaign_details.id', 'filter_id')
//         ->select('campaign_details.id', 'filter_id', DB::raw('count(*) as count'))
//         ->groupBy('filter_id')
//         ->having('count', '>', 1)
//         ->get();

//     foreach ($filters as $filter) {
//         $campaignDetails = CampaignDetail::where('filter_id', '=', $filter['filter_id'])
//             ->select('code_id', 'customer_id')
//             ->get();

//         foreach ($campaignDetails as $campaignDetail) {
//             $customers = Customer::where('id', '=', $campaignDetail->customer_id)
//                 ->whereNotNull('birth')
//                 ->whereMonth('birth', '=', Carbon::now()->addDay(1)->format('m'))
//                 ->whereDay('birth', '=', Carbon::now()->addDay(1)->format('d'))
//                 ->select('firstname','phone','id')
//                 ->get();

//             if ($customers->count() > 0) {
//                 $data = $data->merge($customers);
//             }

//             foreach ($customers as $customer) {
//                 $customersData[$customer->firstname]['id'] = $customer->id;
//                 $customersData[$customer->firstname]['phone'] = $customer->phone;

//                 $code = CampaignDetail::join('codes', 'campaign_details.code_id', '=', 'codes.id')
//                     ->where([
//                         ['codes.id', '=', $campaignDetail->code_id],
//                         ['campaign_details.customer_id', '=', $customer->id],
//                     ])
//                     ->select('code_red', 'reduction')
//                     ->get();

//                 if ($code->count() > 0) {
//                     $customersData[$customer->firstname]['code'] = $code;
//                 }

//                 $CustomerCampaigns = CampaignDetail::where('customer_id', '=', $customer->id)
//                     ->select('id')
//                     ->get();

//                 foreach ($CustomerCampaigns as $CustomerCampaign) {
//                     $message_campaigns = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
//                         ->where('campaign_details_id', '=',  $CustomerCampaign->id)
//                         ->select('messages.id')
//                         ->get();

//                     foreach ($message_campaigns as $message_campaign) {
//                         $message = Message::where('id', '=', $message_campaign->id)
//                             ->select('message')
//                             ->get();

//                         if ($message->count() > 0) {
//                             // $customersData[$customer->firstname]['message'] = $message;
//                             $customersData[$customer->firstname]['message'][] = $message;
//                         }
//                     }
//                 }
//             }
//         }
//     }

//     return $customersData;
//     // return [$data->unique(), $customersData];
// });

// Route::get('/up', function () {
//     $_ = collect();

//     $storeCampaigns = CampaignDetail::where('store_id', '=', '1')
//         ->select('id')
//         ->get();
//     foreach ($storeCampaigns as  $storeCampaign) {
//         $message_campaigns = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
//             ->where('campaign_detail_id', '=',  $storeCampaign->id)
//             ->select('messages.id', DB::raw('count(*) as count'))
//             ->get();
//         return $message_campaigns;
//         $_ = $_ + $message_campaigns['count'];
//     }

//     return $_;
// });

// Route::post('/chart', function (Request $request) {
//     $data = [];
//     $list = [];
//     $lastYear = Carbon::now()->subYear(); // get the date one year ago from now
//     $invoices = Invoice::whereIn('store_id', $request->stores_id)
//         ->where('status', '=', 0)
//         ->whereBetween('invoice_date', [$lastYear, Carbon::now()])
//         ->get()
//         ->groupBy(function ($date) {
//             return Carbon::parse($date->invoice_date)->format('Y-m');
//         });
//     // dd($invoices);
//     foreach ($invoices as $key => $items) {
//         $total = 0;
//         if ($request->type == 0)
//             foreach ($items as $item) {
//                 if ($request->type == 0) {
//                     $total = $total + $item->total;
//                 } elseif ($request->type == 1) {
//                     $total = $total + $item->points;
//                 }
//             }
//         $data['categories'] = $key;
//         $data['total'] = $total;
//         array_push($list, $data);
//     }
//     return collect($list);
// });
// Route::post('/sms', function (Request $request) {

//     $url = 'https://bulksms.ma/developer/sms/send';

//     $fields_string = 'token=a4B2EZpCjP72JwA33JRTwYQ1e&tel=' . $request->num . '&message=' . urlencode($request->texte) . '&shortcode=' . $request->emetteur;
//     // Log::info($fields_string);

//     $ch = curl_init();
//     // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//     $result = curl_exec($ch);
//     if ($result === false) {
//         // An error occurred, handle it here
//         $error = curl_error($ch);
//         curl_close($ch);
//         throw new Exception("curl_exec() failed: $error");
//     }
//     curl_close($ch);
//     // Log::info($result);
//     return $result;
// });
// Route::get('/othai', function () {

//     function setData($store, $customer, $message)
//     {
//         return [
//             'camp_id'     => null,
//             'store_id'    => $store->id,
//             'customer_id' => $customer->id, //nullable
//             'phone'       => $customer->phone,
//             'message'     => $message,
//             'sender'      => $store->sender_id,
//             'start_date'  => Carbon::now(),
//         ];
//     }
//     $customers = Customer::where('store_id', '=', '19')->where('points', '>=', '11')->select('phone')->get();
//     $store = Store::where('id', '=', '19')->get();
//     foreach ($customers as $customer) {
//         $message = "O'THAI TARGA vous souhaite un joyeux mois de Ramadan! Profitez aussi de nos offres promotionnelles.RDV sur notre page Instagram.Tél: 06 00 04 15 06";
//         $data = $this->setData($store, $customer, $message);
//         $this->campaignService->singleCampaign($data);
//     }
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// $message_campaigns = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
// ->where('campaign_details_id', '=',  $filter['id'])
// ->select('messages.id')
// ->get();
// foreach ($message_campaigns as $message_campaign) {
// $message = Message::where('id', '=', $message_campaign->id)
//     ->select('message')
//     ->get();
// if ($message->count() > 0) {
//     $data = $data->merge($message);
// }
// }

// HARD-CODED-MSG
// $data = collect();
// $msg = collect();
// $cd = collect();
// $json = '[
//     {
//         "id": 99,
//         "filter_id": 127
//     },
//     {
//         "id": 100,
//         "filter_id": 127
//     },
//     {
//         "id": 133,
//         "filter_id": 127
//     },
//     {
//         "id": 4,
//         "filter_id": 128
//     }
// ]';

// $filters = json_decode($json, true);

// foreach ($filters as $filter) {
//     $campaignDetails = CampaignDetail::where('filter_id', '=', $filter['filter_id'])
//         ->select('code_id', 'customer_id')
//         ->get();
//     foreach ($campaignDetails as $campaignDetail) {
//         $customers = Customer::where('id', '=', $campaignDetail->customer_id)
//             ->whereNotNull('birth')
//             ->whereMonth('birth', '=', Carbon::now()->addDay(1)->format('m'))
//             ->whereDay('birth', '=', Carbon::now()->addDay(1)->format('d'))
//             ->select('id')
//             ->get();

//         if ($customers->count() > 0) {
//             $msg = $msg->merge($customers);
//         }
//         foreach ($customers as  $customer) {
//             $code = CampaignDetail::join('codes', 'campaign_details.code_id', '=', 'codes.id')
//                 ->where([
//                     ['codes.id', '=', $campaignDetail->code_id],
//                     ['campaign_details.customer_id', '=', $customer->id],
//                 ])
//                 ->select('code_red', 'reduction')
//                 ->get();

//             if ($code->count() > 0) {
//                 $cd = $cd->merge($code);
//             }
//             $CustomerCampaigns = CampaignDetail::where('customer_id', '=', $customer->id)
//             ->select('id')
//             ->get();
//             foreach ($CustomerCampaigns as  $CustomerCampaign) {
//                 $message_campaigns = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
//                 ->where('campaign_details_id', '=',  $CustomerCampaign->id)
//                 ->select('messages.id')
//                 ->get();
//                 foreach ($message_campaigns as $message_campaign) {
//                     $message = Message::where('id', '=', $message_campaign->id)
//                         ->select('message')
//                         ->get();
//                     if ($message->count() > 0) {
//                         $data = $data->merge($message);
//                     }
//                 }
//             }
//         }
//     }
// }
// return [$data->unique(), $msg->unique(), $cd->unique()];

// Route::get('/count', [OwnerController::class, 'getDashboard']);
// Route::get('/test', function () {
//     $campaignService = new CampaignService;
//     $smsDetails = [];
//     $customerApi = [];

//     $CampaignDetails = CampaignDetail::join('campaigns', 'campaign_details.campaign_id', '=', 'campaigns.id')
//         ->where('campaigns.type', '=', 'birthday')
//         ->select('campaign_details.id', 'filter_id', DB::raw('count(*) as count'),'campaigns.type')
//         ->groupBy('filter_id')
//         // ->having('count', '>', 1)
//         ->get();
//     // dd($CampaignDetails);
//     foreach ($CampaignDetails as $CampaignDetail) {
//         // dd($CampaignDetail['type']);
//         $stores = CampaignDetail::where('filter_id', '=', $CampaignDetail['filter_id'])
//             ->select('id', 'store_id')
//             ->groupBy('store_id')
//             ->get();
//         $filter = Filter::where('id', '=', $CampaignDetail['filter_id'])
//             ->first();

//         foreach ($stores as $store) {
//             $messages = MessageCampaign::join('messages', 'message_campaigns.message_id', '=', 'messages.id')
//                 ->where('campaign_detail_id', '=',  $CampaignDetail->id)
//                 ->select('messages.message', 'messages.date_start')
//                 ->orderByDesc('date_start')
//                 ->get();
//             // return $message_campaigns;
//             foreach ($messages as $message) {
//                 // $customers = DB::table('invoices')
//                 // ->join('customers', 'invoices.customer_id', '=', 'customers.id')
//                 $customers = Customer::where([
//                         ['customers.points', '<=', $filter['points']],
//                         ['customers.store_id', '=', $store->store_id],
//                     ])
//                     ->where(function ($query) use ($filter) {
//                         if ($filter['sexe'] == 0) {
//                             $query->where('customers.sexe', '=', 0);
//                         } elseif ($filter['sexe'] == 1) {
//                             $query->where('customers.sexe', '=', 1);
//                         } elseif ($filter['sexe'] == 2) {
//                             $query->where('customers.sexe', '=', 0)
//                                 ->orWhere('customers.sexe', '=', 1);
//                         }
//                     })
//                     // ->whereBetween('invoices.invoice_date', [Carbon::now()->subMonth($filter['last_visit']), Carbon::now()])
//                     // ->whereRaw('TIME(invoices.invoice_date) BETWEEN ? AND ?', [
//                     //     Carbon::parse($filter['heure_start'])->format('H:i:s'),
//                     //     Carbon::parse($filter['heure_end'])->format('H:i:s'),
//                     // ])
//                     ->whereNotNull('customers.birth')
//                     ->whereMonth('customers.birth', '=', Carbon::now()->addDay(1)->format('m'))
//                     ->whereDay('customers.birth', '=', Carbon::now()->addDay($message->date_start )->format('d'))
//                     ->whereBetween('customers.birth', [Carbon::now()->subYears($filter['age_max']), Carbon::now()->subYears($filter['age_min'])])
//                     ->select('customers.id', 'customers.firstname', 'customers.lastname', 'customers.phone')
//                     ->distinct('customers.id')
//                     ->get();
//                 $customerApi[]= $customers;
//                 foreach ($customers as $customer) {
//                     // $smsDetails[$customer->firstname]['date'] = Carbon::now()->addDay($message->date_start)->format('y-m-d');
//                     $sender_id = Store::where('id', '=', $store->store_id)->value('name');
//                     $smsDetails[$customer->firstname]['id'] = $customer->id;
//                     $smsDetails[$customer->firstname]['phone'] = $customer->phone;
//                     $smsDetails[$customer->firstname]['sender_id'] = $sender_id;


//                     $code_red = 0;
//                     // dd($customer->id);
//                     $code = Code::where('customer_id', $customer->id)
//                                         ->whereNotNull('code_red')
//                                         ->first();
//                     if (is_null($code)) {
//                         $code_red = $campaignService->generateCode(6);
//                         $code = Code::create([
//                             'customer_id' => $customer->id,
//                             'code_red' => $code_red,
//                             'status' => 0,
//                         ]);
//                         $smsDetails[$customer->firstname]['code'] = $code_red;
//                     } else {
//                         $code_red = $code->code_red;
//                         $smsDetails[$customer->firstname]['code'] = $code_red;
//                     }

//                     $messageCustomer = $campaignService->Message($message->message, $customer->firstname, $customer->lastname, $code_red);
//                     $smsDetails[$customer->firstname]['messages'][] = $messageCustomer;

//                     $msg = MessageCustomer::create([
//                         'store_id' =>$store->store_id,
//                         'customer_id' => $customer->id,
//                         'message' => $messageCustomer,
//                         'type' => $CampaignDetail['type'],
//                     ]);
//                     $smsDetails[$customer->firstname]['type'] = $CampaignDetail['type'];

//                 }
//             }
//         }
//         return $smsDetails;
//     }
//     // foreach($smsDetails as $smsDetail){
//     //     $campaignService->sendSms($smsDetail['phone'],['messages']['0'],$smsDetail['sender_id']);
//     // }
// });

// Route::get('/envoiUnique', function () {
//     Artisan::call('envoi:unique');
//     return 'Command executed successfully';
// });