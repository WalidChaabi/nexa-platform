<?php

namespace App\Repositories;

use App\Repositories\Contracts\StoreRepositoryInterface;
use App\Models\Store;
use App\Models\Activity;
use App\Models\User;


use DB;

class StoreRepository implements StoreRepositoryInterface
{
  public function all($promo = 0)
  {
    $auth=Auth()->user()->is_admin;
    switch ($auth) {
      case '1':
        $owners=User::where('is_admin','=',2)->where('is_active','=',1)->get();
        $activities=Activity::all();
        break;
      case '2': 
        $owners=User::where('is_admin','=',2)->where('is_active','=',1)->get();
        $activities=Activity::all();
        break;
      default:
        # code...
        break;
    }
    

  }


}
