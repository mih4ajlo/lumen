<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
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

    
/*https://github.com/ankhuve/jobbaextra-backend/blob/master/app/Http/Controllers/DashboardController.php*/

    //
    //TODO meni sa sadrzajem svih tabela (posebne privilegije za user tabelu)
    //TODO lista enumerisanih sadrzaja tabela

    public function index($value='')
    {

              
        //echo "string";
        return view('dashboard.home');
    }

    public function sadrzaj($value='')
    {

               
        $sadrzaj = app('db')->select("SELECT sid, saltnaslov FROM sadrzajs order by sid ");
 
                  
        
        //treba proslediti neki parametar
        return view('dashboard.content',[ "lista"=>$sadrzaj]);
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

    public function reference($value='')
    {
        # code...
    }





}
