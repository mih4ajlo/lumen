<?php 


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate as Auth;

class KeywordController extends Controller
{

	public function list_keywords(Request $request)
	{
		try {
            $unosi = app('db')->select("SELECT * FROM keywords "  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
        
        //var_dump($content_id);    
        return view(
            "keywords.listKeyword", ["lista_keywords"=>$unosi] 
        );
	}


	public function edit_keyword(Request $request,$keyword_id)
	{
		try {
            $res = app('db')->update('UPDATE keywords SET keyword=?,   keyword_cir=?, kategorija=?  where id = ?', [ $_POST['keyword'],$_POST['keyword_cir'],$_POST['kategorija'] ,$keyword_id ] );

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

    $out = "Izmena uspesna. Vratite se <a href='../../acters#acter{$id_aktera}'>ovde</a>." ;

        $title = "Editor keywords";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}

	public function delete_keyword(Request $request, $keyword_id)
	{
		# code...
	}


	public function add_keyword(Request $request)
	{
		  try {
            $res = app('db')->insert('INSERT INTO keywords (keyword, keyword_cir,kategorija) VALUES (?, ?, ? )  ', [$_POST['keyword'],$_POST['keyword_cir'],$_POST['kategorija']]);

          
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }

    $out = "Keywords dodat. Vratite se <a href='../acters'>ovde</a>." ;

        $title = "Keywords aktera";
        $head ="";
        return view( "content.Display",["content"=>$out,"title"=>$title,"head"=>$head ]);
	}
	
} 


?>