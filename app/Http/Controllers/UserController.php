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
            
            #!! ispravan
            //insert into sessions (user_id, ip_adress, user_agent, payload, last_activity)
            $session_id = "ses_".uniqid();

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $ip_of_user = $_SERVER['REMOTE_ADDR'];
                    
            
            try {
                $status = app('db')->insert(
                "INSERT INTO sessions(  user_id, ip_address, user_agent, payload, last_activity) VALUES (?,?,?,?,?) ", 
                [$session_id, $ip_of_user,$user_agent, "",'1' ] );

            } catch (Exception $e) {
                
            }

                  
            
            if(!$status){ ;/*error - problem sa bazom*/}
  
            setcookie("sesid", $session_id, time() + (60*60*2 ), "/");
            //setcookie("sesid", $session_id, 1000*60 *60 );
            
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

    public function single(Request $request, $content_id )
    { 
        try {
            $unos = app('db')->select("SELECT * FROM users where uid=? ",[$content_id] );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
        
        //var_dump($content_id);    
        return view(
            "user.user", ["user_unos"=>$unos] 
        );

    }

    public function list_users(Request $request)
    { 
        try {
            $unosi = app('db')->select("SELECT * FROM users   "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
        
        //var_dump($content_id);    
        return view(
            "user.listUser", ["lista_usera"=>$unosi] 
        );
    }

    public function delete_user(Request $request, $user_id)
    {
        return "deleted";
    }
}
