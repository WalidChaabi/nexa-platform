<?php
namespace App\Services;

use App\Models\SmsCrossSelling;

class SmsCrossSellingService
{

public function create(array $smsCross)
{
   return  SmsCrossSelling::create($smsCross);
}

public static function getByStoresId(array $storesId){
  return  SmsCrossSelling::whereIn('store_id',$storesId)->where('status',0)->get();
}

}
