<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $credentials = $request->only('email', 'pass');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return ['result' => 'ok'];
        }

        return ['result' => 'not ok'];
    }


    public function register(Request $request)
    {
       
            
        return ['result' => 'not ok'];
    }
}
