<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Middleware\Authenticate as Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

   public function login( Request $request)
    { 
 
        $this->validate($request, [
            'email'    => 'required|email',
            'pass' => 'required',
        ]);

       
        $sifra = $request->input('pass');
        $mail = $request->input('email');

        
        //$sifra 
        // $sifra =  password_hash( /*$sifra*/ 'neki pass' , PASSWORD_DEFAULT);
        $hash = app('db')->select("SELECT * FROM users where email = ?  ", 
            [$mail ] );

        
        if( empty($hash) ){
            return ['result' => 'nema email'];
        }

     
        if (password_verify( $sifra, $hash[0]->pass )) {
          
                return ['result' => 'ok'];
          
            
        } else {
            return ['result' => 'ne poklapaju se sifre'];
        }
              
        
        
    }


    public function register(Request $request)
    {

          $sifra =  password_hash( $sifra /*'neki pass'*/ , PASSWORD_DEFAULT);
            
        return ['result' => 'not ok'];
    }
}
