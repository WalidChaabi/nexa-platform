<?php 
namespace App\Services;
use App\Models\StoreOffre;
class StoreOffreService 
{
    public function getAll()
    {
        return StoreOffre::OrderBy('id','DESC')->get();
    }

    /**
     * @param StoreOffre $storeOffre
     * @return Collection 
     */
    public function store($storeOffre)
    {
        return StoreOffre::create($storeOffre);
    }
}