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
       
        return view(
            "keyword.listKeywords", ["lista_keywords"=>$unosi] 
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


        return response()->json(['keyword_id' =>$keyword_id , 'state' => 'CA']);
 
	}

	public function delete_keyword(Request $request, $keyword_id)
	{  
        
		try {
            $unosi = app('db')->delete("DELETE  FROM keywords where id=? ", [$keyword_id]  );    
        } catch (Exception $e) {
            print_r("<pre>");
            var_dump($e);
            print_r("</pre>");
            //die();
        }
              
        return response()->json(['keyword_id' =>$keyword_id , 'kolicina'=> $unosi, 'state' => 'izbrisano']);

	}


    //na foru akters
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

        return response()->json(['keyword_id' =>$res , 'state' => 'dodato']);
      
	}
	
} 


?>