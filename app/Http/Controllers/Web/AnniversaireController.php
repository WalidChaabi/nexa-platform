<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AnniversaireController extends Controller
{
    // protected $calendarService;

    // public function __construct()
    // {
    //     $this->calendarService = new CalendarService;
    // }

    // public function getEvents(int $activityId){
    //  return $this->calendarService->getEventsByActivityId($activityId);
    // }

    public function index()
    {
        $store_id = auth()->user()->store->id;

        $customers_today = Customer::whereDay('birth', Carbon::now()->format('d'))
            ->where('store_id', $store_id)
            ->whereMonth('birth', Carbon::now()->format('m'))
            ->select('firstname', 'lastname', 'birth', 'phone')
            ->get();
        // dd($customers_today);
        $customers_week = collect();
        $i = 0;

        do {
            $customers = Customer::whereDay('birth', Carbon::now()->addDay($i)->format('d'))
                ->whereMonth('birth', Carbon::now()->addDay($i)->format('m'))
                ->where('store_id', $store_id)
                ->select('firstname', 'lastname', 'birth', 'phone')
                ->get();

            $customers_week = $customers_week->merge($customers);
            $i++;
        } while ($i <= 7);

        $customers_month = Customer::whereMonth('birth', Carbon::now()->format('m'))
        ->where('store_id', $store_id)
        ->select('firstname', 'lastname', 'birth', 'phone')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->birth)->format('d'); // Group by birth day
        })
        ->sortKeysDesc();
        $mergedCollection = new Collection();

        foreach ($customers_month as $collection) {
            foreach ($collection as $item) {
                $mergedCollection->push($item);
            }
        }
        return view('employe.anniversaire.index', compact('customers_today', 'customers_week', 'mergedCollection'));
    }


    // public function indexOwner(){
    //     $owner  = auth()->user();
    //     $stores = $owner->stores;
    //     $customers = CustomerService::getSharedCustomer($owner);
    //     // dd($customers);
    //     return view('owner.calendar.index',['events'=>$this->getEvents(1),'customers'=>$customers,'stores'=>$stores]);
    // }
}
