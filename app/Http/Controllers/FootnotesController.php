<?php 


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate as Auth;

class FootnotesController extends Controller
{

public function list_footnotes(Request $request)
	{


		try {
            $unosi = app('db')->select("SELECT * FROM footnotes "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
              
        
        //var_dump($content_id);    
        return view(
            "footnote.listFootnote", ["lista_footnotes"=>$unosi] 
        );
	}


	public function edit_footnote(Request $request,$footnote_id)
	{
		try {
            $res = app('db')->update('UPDATE footnotes SET   fcont=?, fyear=?  where fid = ?', [ $_POST['fcont'],$_POST['fyear'] ,$footnote_id ] );

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

        $out = "Izmena uspesna. Vratite se <a href='../../footnote#acter{$footnote_id}'>ovde</a>." ;

        $title = "Editor footnotes";
        $head ="";
        return view( "footnote.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}

	public function delete_footnote(Request $request, $footnote_id)
	{
		# code...
	}


    //na foru akters
	public function add_footnote(Request $request)
	{
		  try {
            $res = app('db')->insert('INSERT INTO footnotes ( fcont,fyear) VALUES (  ?, ? )  ', [ $_POST['fcont'],$_POST['kategorija']]);

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

    $out = "Footnotes dodat. Vratite se <a href='../footnote'>ovde</a>." ;

        $title = "Footnotes aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}



}

?>
