<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate as Auth;

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
        $hash = app('db')->select(
            "SELECT * FROM users where email = ?  ", 
            [$mail ] );

        

        if( empty($hash) ){
            return redirect("/auth/error/email");
            //return ['result' => 'nema email'];
        }

     
        if (password_verify( $sifra, $hash[0]->pass )) {
            
               
            
            //setuje se session?
            return redirect("/dashboard");
                //return ['result' => 'ok'];
          
            
        } else {
            return redirect("/auth/error/sifra");
            //return ['result' => 'ne poklapaju se sifre'];
        }
              
        
        
    }

    public function doLogin($value='')
    {

      /*          print_r("<pre>");
                var_dump(get_defined_vars());
                print_r("</pre>");
                die();*/
        
        return view(
            "auth.login"
        );
    }

    public function logout( $value='' )
    {        
        return redirect("/");       
    }


    public function register(Request $request)
    {

        $sifra =  password_hash( $sifra /*'neki pass'*/ , PASSWORD_DEFAULT);
            
        return ['result' => 'not ok'];
    }

    public function error( $err )
    {
        
        return view(
            "auth.greska", 
            ["poruka"=>$err]
        );
    }
}
