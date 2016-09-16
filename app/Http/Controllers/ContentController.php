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




    public function saveTree(Request $request)
    {

        
       echo "uspesno";
    }



    public function list_content2(Request $request)
    {

        
        return view(
            "content.listContent2"
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

    public function edit_content(Request $request)
    {

        $no_tags = strip_tags( $_POST['textSadrzaj'] );
     
        try {

            // saltorder =?, surl=?, sordernatural=?,
            $res = app('db')->update('UPDATE sadrzajs SET  scont=?, scont_notag=? , sgodina =?, kid=?, saltnaslov=?,skeywords=?, slang=? where  sid=? ', 
                array( 
                    $_POST['textSadrzaj'],
                    $no_tags, $_POST['textGodina'],  
                    $_POST['textKategorija'],
                    $_POST['textNaslov'],  
                    $_POST['textKeywords'],  
                    $_POST['selectJezik'], 
                    $_POST['sid'] 
                    ) 
                );

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

               

        return response()->json(['keyword_id' =>$res ,  'state' => 'updateovano']);

        /*$out = "Izmena uspesna. Vratite se <a href='../../content/{$referenca_id}'>ovde</a>." ;

        $title = "Editor content";
        $head ="";
        return view( "referenca.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);*/
    }


    public function new_content($value='')
    {
        //showCategoriesOrder();
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






}