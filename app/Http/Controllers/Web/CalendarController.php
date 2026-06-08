<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Services\CalendarService;
use App\Services\CustomerService;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct()
    {
        $this->calendarService = new CalendarService;
    }

    public function getEvents(int $activityId){
     return $this->calendarService->getEventsByActivityId($activityId);
    }

    public function index(){
        $store = Store::find(auth()->user()->store_id);
        $owner = User::where('id', '=', $store->user_id)->first();
        $customers = CustomerService::getSharedCustomer($owner);
        return view('employe.calendar.index',['events'=>$this->getEvents(1),'customers'=>$customers]);
    }


    public function indexOwner(){
        $owner  = auth()->user();
        $stores = $owner->stores;
        $customers = CustomerService::getSharedCustomer($owner);
        // dd($customers);
        return view('owner.calendar.index',['events'=>$this->getEvents(1),'customers'=>$customers,'stores'=>$stores]);
    }
}
