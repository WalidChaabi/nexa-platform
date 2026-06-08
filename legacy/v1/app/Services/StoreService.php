<?php 
namespace App\Services;

use App\Models\Product;
use App\Models\Store;
class StoreService 
{
    /**
     * @return Collection
     */
  public function getAll()
  {
    return Store::orderBy('id','DESC')->get();
  }

  public function getStoresByOwner(int $owner){
       return Store::where('user_id',$owner)->get();
  }

  public function getStoresByIds(array $ids){
    return Store::whereIn('id',$ids)->get();
}
        
}