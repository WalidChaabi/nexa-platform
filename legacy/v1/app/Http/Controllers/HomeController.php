<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('contactUs');
    }
    
    public function contactUs(Request $request)
    {
        
        // return $request->all();
        
        
        \Mail::send('comming-soon',
             [
                 'name' =>$request->name,
                 'email' => $request->email,
                 'subject' => 'Consultation Web',
                 'phone' => $request->phone,
             ]
                
             , function($message) use ($request)
              {
                  $message->from($request->email);
                  $message->to('othmane.lahbiri@nexa.app');
              }
               
            );
               return redirect('/');
        
    }

   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
