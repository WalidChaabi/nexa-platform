<?php

namespace App\Services;

use App\Models\HistoriquePaiement;

class HistoriquePaiementService
{
    public function create($historiquePaiement, $cmd = null, $status = 0)
    {
        if (!$cmd) {
            HistoriquePaiement::where('cmd', $cmd)->update(['status' => $status]);
        } else {
            return HistoriquePaiement::create($historiquePaiement);
        }
    }


    public function getAllByOwnerId(int $ownerId)
    {
        return HistoriquePaiement::where('owner_id', $ownerId)->get();
    }

    public function getByInvoiceId(string $cmd)
    {
        return HistoriquePaiement::where('cmd', $cmd)->get()->first();
    }

    public function find(string $id)
    {
        return HistoriquePaiement::find($id);
    }

    public function getAll(){
        return HistoriquePaiement::orderBy('id','desc')->with('owner')->get();
    }
}
