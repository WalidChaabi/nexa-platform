<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayementController extends Controller
{
    public function pay(){
        return view('payement.index');
    }

    public function checkout()
    {
        return view('payement.validate');
    }
}
