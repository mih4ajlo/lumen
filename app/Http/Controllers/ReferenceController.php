<?php 


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate as Auth;

class ReferenceController extends Controller
{

public function list_referenca(Request $request)
	{


		try {
            $unosi = app('db')->select("SELECT * FROM refs "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
                          
                
              
        
        //var_dump($content_id);    
        return view(
            "referenca.listReferenca", ["lista_referenca"=>$unosi] 
        );
	}


	public function edit_referenca(Request $request,$referenca_id)
	{
		try {
            $res = app('db')->update('UPDATE refs SET   knaziv=?, korder=?, kgodina=?   where kid = ?', [ $_POST['knaziv'],$_POST['korder'] , $_POST['kgodina'],$referenca_id ] );

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

        $out = "Izmena uspesna. Vratite se <a href='../../referenca#referenca{$referenca_id}'>ovde</a>." ;

        $title = "Editor referenca";
        $head ="";
        return view( "referenca.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}

	public function delete_referenca(Request $request, $referenca_id)
	{
		# code...
	}


    //na foru akters
	public function add_referenca(Request $request)
	{
		  try {
            $res = app('db')->insert('INSERT INTO refs ( knaziv, korder, kgodina  ) VALUES (  ?, ?, ? )  ', [$_POST['knaziv'],$_POST['korder'] , $_POST['kgodina']]);

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

    $out = "Referenca dodat. Vratite se <a href='../referenca'>ovde</a>." ;

        $title = "Referenca aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}



}

?>
