<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HistoriquePaiementService;
use PDF;

class HistoriquePaiementController extends Controller
{
    protected $historiquePaiementService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->historiquePaiementService = new HistoriquePaiementService;
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('owner.historique-paiements.index',['paiements'=>$this->historiquePaiementService->getAllByOwnerId(auth()->user()->id)]);
    }


    public function indexAdmin(){
        return view('admin.historique-paiements.index',['paiements'=>$this->historiquePaiementService->getAll()]);
    }

  

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $historiquePaiement =   $this->historiquePaiementService->find($id);
        $pdf =  PDF::loadView('emails.historique-paiement', array('content' =>$historiquePaiement))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download("#PF" . $id . ".pdf");

    }

}
