<?php

namespace App\Services;

use App\Models\Rdv;

class RdvService
{

    public function create(array $rdv)
    {
      return Rdv::create($rdv);
    }

    public function update(array $rdv){
        $newRdv = $this->findById($rdv['id']);
        $newRdv->update($rdv);
        $newRdv->refresh();
        return $newRdv;
    }

    public function updateStartDate(int $rdvId,$start){
        $event = $this->findById($rdvId);
        $event->update([
            'start'=>$start,
        ]);
        $event->refresh();
        return $event;
    }

    public function findById(int $rdvId)
    {
        return Rdv::findOrFail($rdvId);
    }

    public function getAll(){
        return Rdv::where('status',0)->get();
    }

    public function getAllByStoresId(array $storesId){
        $storesById = Rdv::where('status',0)->whereIn('store_id',$storesId)->get();
        // dd('storesById',$storesById);
        return $storesById;
    }

    public function delete(int $rdvId){
        return Rdv::destroy($rdvId);
    }
    
    public function get30MinBefore(){
        return Rdv::whereBetween('start', [now(),now()->addMinute(30)])->where('status',0)->get();
    }
}
