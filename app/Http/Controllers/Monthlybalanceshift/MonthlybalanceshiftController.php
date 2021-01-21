<?php
namespace App\Http\Controllers\Monthlybalanceshift;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Http\Requests\FileFlagRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Auth;
use Config;
use Crypt;
use DB;
use HTML;
use Illuminate\Support\Facades\Log;
use Input;
use Lang;
use League\Csv\Reader;
use Mail;
use Redirect;
use Response;
use Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use URL;
use View;
use Illuminate\Http\Request;
use Gate;
use File;
use Excel;
use SFTP;
use PDO;
use Validator;


use App\Models\Monthlybalanceshift;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class MonthlybalanceshiftController extends Controller
{


    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */

    public function getIndex()
    {
        if( Input::has('start_date') || Input::has('end_date') ){
            $validator = Validator::make(Input::all(), [                
                'end_date' => 'date|after_or_equal:start_date',
                'start_date' => 'date|before_or_equal:end_date',        
            ]);
            if ($validator->fails()) {            
                $error = $validator->errors()->first();                  
                return Redirect::back()->withInput(Input::all())->with("error",$error);                
            }    
        }  

        $filterColumn = array();
       
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));

        return View::make('monthlybalanceshift/index')->with('filterColumn', $filterColumn);        
    }

    public function getDatatable()
    {        
        $params = array();
        $result = array('count'=>'0','data'=>array());        
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }        
        if( ( Input::has('start_date') && Input::has('end_date') )){
            $result = Monthlybalanceshift::getDatatableData($params);
        }
        $rows = array();
        foreach ($result['data'] as $data) {

            $nestedData = array();
            $nestedData['pan'] = $data->pan;
            
            if(array_key_exists($data->pan, $result['openingBal']))
                $nestedData['opening_ac_bal'] = number_format($result['openingBal'][$data->pan],2, '.', '');
            else
                $nestedData['opening_ac_bal'] = "0.00";

            $nestedData['ATM_Settled'] = number_format($data->ATM_Settled,2, '.', '');
            $nestedData['POS_Settled'] = number_format($data->POS_Settled,2, '.', '');
            $nestedData['ATM_FEE'] = number_format($data->ATM_FEE,2, '.', '');
            $nestedData['FPIN'] = number_format($data->FPIN,2, '.', '');
            $nestedData['FP_out'] =number_format($data->FP_out, 2, '.', '');
            $nestedData['FP_out_fee'] = number_format($data->FP_out_fee,2, '.', '');
            $nestedData['Other_fees'] = number_format($data->Other_fees,2, '.', '');
            $nestedData['Load_Unload'] = number_format($data->Load_Unload,2, '.', '');
            $nestedData['Blocked_Amount'] = number_format($data->Blocked_Amount,2, '.', '');
            $nestedData['Balance_Adj'] = number_format($data->Balance_Adj,2, '.', '');
            $closing_ac_bal_calc = $nestedData['opening_ac_bal'] - $nestedData['ATM_Settled'] - $nestedData['POS_Settled'] - $nestedData['ATM_FEE'] + $nestedData['FPIN'] - $nestedData['FP_out'] - $nestedData['FP_out_fee'] - $nestedData['Other_fees'] + $nestedData['Load_Unload'] + $nestedData['Blocked_Amount'] + $nestedData['Balance_Adj'];

            $nestedData['closing_ac_bal_calc'] = number_format($closing_ac_bal_calc,2, '.', '');

            if(array_key_exists($data->pan, $result['closingacbalgps']))
                $nestedData['closing_ac_bal_gps'] = number_format($result['closingacbalgps'][$data->pan],2, '.', '');
            else
                $nestedData['closing_ac_bal_gps'] = "0.00";

            $diffCheck = $nestedData['closing_ac_bal_gps'] - $nestedData['closing_ac_bal_calc'];

            $nestedData['trans_settled_not_adj_gps'] = "0.00";
            $nestedData['trans_settled_not_adj_gps_2'] = "0.00";
            if($diffCheck < 0)
                $nestedData['trans_settled_not_adj_gps'] = number_format($diffCheck,2, '.', '');
            else
                $nestedData['trans_settled_not_adj_gps_2'] = number_format($diffCheck,2, '.', '');
            
            $diffamt  = $closing_ac_bal_calc - ($nestedData['closing_ac_bal_gps'] + $nestedData['trans_settled_not_adj_gps'] + $nestedData['trans_settled_not_adj_gps_2'] );

             $nestedData['diff'] = number_format($diffamt,2, '.', '');                
            $rows[] = $nestedData; 

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }


    // public function getIndex()
    // {
    // 	if( Input::has('reportMonth')){
    //         $validator = Validator::make(Input::all(), [                
    //             'reportMonth' => 'required',       
    //         ]);
    //         if ($validator->fails()) {            
    //             $error = $validator->errors()->first();                  
    //             return Redirect::back()->withInput(Input::all())->with("error",$error);                
    //         }    
    //     }  

    //     $filterColumn = array();
       
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));
    //     $filterColumn[]=array("filter" => array("type" => "input"));

    //     return View::make('monthlybalanceshift/index1')->with('filterColumn', $filterColumn);     
    // }

    // public function getDatatable(Request $request)
    // {
    //     $params = array();
    //     $result = array('count'=>'0','data'=>array());        
    //     if(Input::has('reportMonth') ){
    //         $params['reportMonth'] = Input::get('reportMonth');    
    //     }   

    //     $result = Monthlybalanceshift::getDatatableData($params);
        
    //     $rows = array();
    //     foreach ($result['data'] as $data) {

    //         $nestedData = array();
    //         $nestedData['report_month'] = $data->report_month;
    //         $nestedData['pan'] = $data->pan;
    //         $nestedData['opening_ac_bal'] = round($data->opening_ac_bal,2);
    //         $nestedData['ATM_Settled'] = round($data->ATM_Settled,2);
    //         $nestedData['POS_Settled'] = round($data->POS_Settled,2);
    //         $nestedData['ATM_FEE'] = round($data->ATM_FEE,2);
    //         $nestedData['FPIN'] = round($data->FPIN,2);
    //         $nestedData['FP_out'] = round($data->FP_out,2);
    //         $nestedData['FP_out_fee'] = round($data->FP_out_fee,2);
    //         $nestedData['Other_fees'] = round($data->Other_fees,2);
    //         $nestedData['Load_Unload'] = round($data->Load_Unload,2);
    //         $nestedData['Blocked_Amount'] = round($data->Blocked_Amount,2);
    //         $nestedData['Balance_Adj'] = round($data->Balance_Adj,2);
    //         $nestedData['closing_ac_bal_calc'] = round($data->closing_ac_bal_calc,2);
    //         $nestedData['closing_ac_bal_gps'] = round($data->closing_ac_bal_gps,2);
    //         $nestedData['Transactions_in_Timing'] = round($data->Transactions_in_Timing,2);
    //         $nestedData['Transactions_in_Timing2'] = round($data->Transactions_in_Timing2,2);
    //         $nestedData['diff'] = round($data->diff,2);                
    //         $rows[] = $nestedData; 

    //     }

    //     return array('total'=>$result['count'], 'rows'=>$rows);  
    // }

    public function getRegenerateMonthlyBalanceShift()
    {
        return View::make('monthlybalanceshift/import');
    }

    public function postRegenerateMonthlyBalanceShift(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',         
        ]);
        
        if ($validator->fails()) {
            $error = $validator->errors()->first();            
            return redirect()->to("monthlybalanceshift/recalculate")->with("error",$error);                
        }
        $reportMonth = $request->get('start_date');
        $dtArray = explode("-", $reportMonth);
        $dtmonth = $dtArray[0];
        $dtyear = $dtArray[1];

        $this->calculateMonthlyBalanceShift($dtmonth,$dtyear,$reportMonth);
        return redirect()->to("monthlybalanceshift/recalculate")->with("success","Record re-calculated");  
    }

    public function generateMonthlyBalanceShift()
    {
        $dtmonth = "11";
        $dtyear = "2017";
        $reportMonth = "11-2017";
        $this->calculateMonthlyBalanceShift($dtmonth,$dtyear,$reportMonth);
        echo "success";exit;
    }

    public function calculateMonthlyBalanceShift($dtmonth,$dtyear,$reportMonth)
    {   
        $this->callStoredProcedure($dtmonth,$dtyear,$reportMonth);
    }

    private function callStoredProcedure($dtmonth,$dtyear,$reportMonth)
    {
        $procedureSet = DB::select("CALL `monthlyBalanceShift`('$dtmonth','$dtyear','$reportMonth');");
    }

}
