<?php
namespace App\Services;

use App\Models\Categorie;

class CategorieService {

    public static function all()
    {
        return Categorie::where('store_id',auth()->user()->store->id)->get();
    }

    public function getByStoreId($storeId){
        return Categorie::where('store_id',$storeId)->get();
    }

    public function create(array $Categorie){
        return Categorie::create($Categorie);
    }
}
