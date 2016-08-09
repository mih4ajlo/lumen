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
        return view('dashboard');
    }

    public function sadrzaj($value='')
    {

           
        
       /* $sadrzaj = app('db')->select("SELECT * FROM sadrzajs where id = ? and length(content) > ? ", [2, 20]);*/

        //treba proslediti neki parametar
        return view('dashboard');
    }





}
