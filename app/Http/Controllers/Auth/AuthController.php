<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
      
        $data=$request->all();
       
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(auth()->attempt(array('email' => $data['email'], 'password' => $data['password'])))
        {
            if (auth()->user()->is_admin == 1) {
                return redirect()->route('index');
            }elseif(auth()->user()->is_admin == 2){
                $owner = User::find(auth()->user()->id)->update(['last_activity' => Carbon::now()]);           
                return redirect(route('owner.dashboard'));
            }elseif(auth()->user()->is_admin==3){
                $employe = User::find(auth()->user()->id);
                $employe->update(['last_activity' => Carbon::now()]);
                
                return redirect('dashboard/employe');
            }
        }else{
            return view('authentication.login')->with('errors_auth',trans('Mot de passe ou E-mail est incorrecte'));
        }
    }

    public  function getLogin(){
        return view('authentication.login');
    }

    
}
