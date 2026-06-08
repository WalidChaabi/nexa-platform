<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail as mail;

class GuestController extends Controller
{
    public function index()
    {
        return view('guest.index');
    }
    public function mention()
    {
        return view('guest.mentions');
    }
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'commerce'=> 'required',
            'phone' => 'required',
            'ville' => 'required',
        ],
        [
            'name.required' => 'Le champ :attribute est obligatoire',
            'commerce.required' => 'Le champ nom du :attribute est obligatoire',
            'phone.required' => 'Le champ :attribute est obligatoire',
            'ville.required' => 'Le champ :attribute est obligatoire'
        ]);
        // dd('ok');
        // Email::create($request->all());
        $adminEmail = "wachaabi98@gmail.com";
        mail::to($adminEmail)->send(new ContactMail((object) $request->all()));

        return redirect()->back();
    }
}
