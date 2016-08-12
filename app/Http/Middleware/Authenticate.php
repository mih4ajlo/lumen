<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
        
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //$plan  = $request->headers->get('user-agent');
       

               
       
        $cookie =  $_COOKIE['sesid'] ;

                
        if(!empty($cookie) ){

            
          $user =  app('db')->select(
            "SELECT * FROM sessions where user_id = ?  ", 
            [ $cookie ] );

      

            if($user[0]->ip_address == $_SERVER['REMOTE_ADDR'])
                {
                 return $next($request);
                }
            
         
          
        }

        

        return redirect("/");

        //setcookie("sesid", "pera", time() + (86400 * 30), "/");

                      
        
        //return response('Unauthorized.', 401);

        
        /*if ($this->auth->guard($guard)->guest()) {
            return response('Unauthorized.', 401);
        }*/

        
    }

    public function doLogout($value='')
    {
       /* print_r("<pre>");
        var_dump(get_defined_vars());
        print_r("</pre>");
        die();*/

    }
}
