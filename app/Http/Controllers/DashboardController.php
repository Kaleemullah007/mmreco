<?php
namespace App\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Actionlog;
use View;
use Auth;
use Redirect;
use App\Models\User;
use Gate;
use App\Models\Location;
use App\Helpers\Helper;
use Input;
use DB;
use Session;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Settelementsummary\SettelementsummaryController;
use App\Http\Controllers\Autocomparetxn\AutocomparetxnController;
use App\Http\Controllers\Mainrecoreportdaily\MainrecoreportdailyController;

class DashboardController extends Controller
{

    public function getIndex()
    {
           
            return View::make('dashboard');
        
    }

    public function viewAllNotification()
    {
       
    	return view('layouts.allnotification');
    }

    public function getGenReportInRange(Request $request)
    {
    	return View::make('generaterange');
    }
   
    public function postGenReportInRange(Request $request)
    {
		
    	$validator = Validator::make(Input::all(), [                
            'end_date' => 'date|after_or_equal:start_date',
            'start_date' => 'date|before_or_equal:end_date',        
        ]);
        if ($validator->fails()) {            
            $error = $validator->errors()->first();                  
            return Redirect::back()->withInput(Input::all())->with("error",$error);                
        }    

      	$startTime = strtotime(Input::get('start_date'));
      	$endTime = strtotime(Input::get('end_date'));
      	while ($startTime <= $endTime) 
      	{
		
			  $dayName = date("D",$startTime); 
			  //day must not be saturday or sunday
	      	if($dayName != "Sat" && $dayName != "Sun")
	      	{
	      		$currentDate = date("Y-m-d",$startTime); 
	      		if(!empty(Input::get('sattelment')) && Input::get('sattelment') == 1)
	      		{
					
	      			$obj = new SettelementsummaryController();
	      			$obj->calculateSettelementSummary($currentDate);
	      		}

	      		if(!empty(Input::get('autocmpr')) && Input::get('autocmpr') == 1)
	      		{
					$startDate = date("Y-m-d",$startTime);
					$endDate =date('Y-m-d',$endTime);
				
;
	      			$autoCmpObj = new AutocomparetxnController();
	      			$autoCmpObj->autoMapTxnData($currentDate);
	      		}

	      		if(!empty(Input::get('mainreco')) && Input::get('mainreco') == 1)
		      	{
					
		      		$mainrecoObj = new MainrecoreportdailyController();
		      		$mainrecoObj->calculateMainRecoDaily($currentDate);
		      	}
	      	}
	      	$startTime += strtotime('+1 day', 0);
      	}
      	
      	return redirect()->to("regeneratereport")->with("success","Record re-calculated"); 
    }
   
}
