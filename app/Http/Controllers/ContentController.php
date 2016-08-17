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


		try {
			$unos = app('db')->select("SELECT * FROM sadrzajs where sid=? ",[$content_id] );	
		} catch (Exception $e) {
			print_r("<pre>");
			var_dump($e);
			print_r("</pre>");
			//die();
		}
		
		//var_dump($content_id);	
		return view(
            "content.content", ["sadrzaj_unos"=>$unos] 
        );

	}

    public function list_content(Request $request)
    {
        
        try {
            $unosi = app('db')->select("SELECT * FROM sadrzajs   "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
        
        //var_dump($content_id);    
        return view(
            "content.listContent", 
            ["lista_unosa"=>$unosi] 
        );
    }

    public function new_content($value='')
    {
        # code...
    }
    

    public function delete_content(Request $request, $content_id)
    {
       //neka potvrda, ajax
       return "izbrisano";
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


//acters
    public function list_acters(Request $request)
    {

        try {
            $akteri = app('db')->select("SELECT * FROM akters   "  );
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

$out ='
<div class="row">
				<div class="ta-right" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>

			<table class="table table-condensed">
			<tr>
				<th>Kategorija</th>
				<th>Naziv</th>
				<th>Tagovi</th>
				<th>Godina</th>
				<th>Izmeni</th>
				<th>Obrisi</th>
			</tr>
';



					foreach ($akteri as  $value) {

						$temp_id = $value->aid;

						$ikonica_edit =  '<span class="glyphicon glyphicon-pencil" content="'.$temp_id.'" aria-hidden="true"></span>';

						$ikonica_delete =  '<span class="glyphicon glyphicon-remove" content="'.$temp_id.'" aria-hidden="true"></span>';


						$link_edit = "<a href='acters/$temp_id'>$ikonica_edit</a>";

						//ajax potvrda akcije
						$link_del = "<a href='acters/delete/$temp_id'>$ikonica_delete</a>";

						$out .= print_r("<tr>"
							."<td>{$value->akategorija}</td> "
							."<td>{$value->anaziv}</td>"
							."<td>{$value->atags}</td>"
							."<td>{$value->agodina}</td>"
							."<td>$link_edit</td>"
							."<td>$link_del</td>"
							."</tr>",true);

					}

$out .= '
			</table>
			<div class="row">
				<div class="ta-right" >
					<span>Dodaj unos </span>
					<span class="glyphicon glyphicon-plus"  aria-hidden="true"></span>
				</div>
			</div>
';


        $title = "Test title za aktere iz kontrolera";
        $head ="";
        return view(
            "content.Display",["out"=>$out,"title"=>$title,"head"=>$head ]
        );
    }


}
