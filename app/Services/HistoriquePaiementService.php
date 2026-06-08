<?php

namespace App\Services;

use App\Models\HistoriquePaiement;

class HistoriquePaiementService
{
    public function create($historiquePaiement, $cmd = null, $status = 0)
    {
        if ($cmd) {
            $existing = HistoriquePaiement::where('cmd', $cmd)->first();
            if ($existing) {
                $existing->update(['status' => $status]);

                return $existing;
            }
        }

        if (!$historiquePaiement || !is_array($historiquePaiement)) {
            return null;
        }

        $historiquePaiement['status'] = $status;
        if ($cmd) {
            $historiquePaiement['cmd'] = $cmd;
        }

        return HistoriquePaiement::create($historiquePaiement);
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
