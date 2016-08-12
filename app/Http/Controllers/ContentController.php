<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware(['auth']);
    }



    public function index($value='')
    {
 			
              
        //echo "string";
        return view('dashboard.home');
    }


	public function single(Request $request, $content_id )
	{

				print_r("<pre>");
				var_dump(get_defined_vars());
				print_r("</pre>");
				die();
		
		 $unos = app('db')->select("SELECT * FROM sadrzajs where sid=? ",[$content_id] );
   
		//var_dump($content_id);	
		return view(
            "content.content", ["sadrzaj_unos"=>$unos] 
        );

	}
    

    public function kategorije( $value='' )
    {
        
        $kategorije = app('db')->select("SELECT kid, knaziv FROM kategorijes where 1 " );
   

         return view('dashboard.kategorije',[ "kategorije"=>$kategorije]);
    }

    public function users($value='')
    {
        $users = app('db')->select("SELECT * FROM users where 1 " );

        return view('dashboard.users',[ "users"=>$users]);
    }





}
