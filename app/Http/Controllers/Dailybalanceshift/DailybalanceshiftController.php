<?php
namespace App\Http\Controllers\Dailybalanceshift;

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
use Session;

use App\Models\Agencybanking;
use App\Models\Agencybankingfee;
use App\Models\Cardauthorisation;
use App\Models\Cardbaladjust;
use App\Models\Cardchrgbackrepres;
use App\Models\Cardevent;
use App\Models\Cardfee;
use App\Models\Cardfinancial;
use App\Models\Cardloadunload;
use App\Models\Mastercardfee;
use App\Models\Settelementsummary;
use App\Models\Bankstatement;
use App\Models\Bankbalance;
use App\Models\Dailybalanceshift;
use App\Models\Dailybalcardfinancialint;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class DailybalanceshiftController extends Controller
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
        $TransactionDrop = array("Cardfinancial" => "Card Financial" , "Cardfee" => "Card Fee");

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
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));

        $Cardfinancial  = array();

        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));        
        $Cardfinancial[]=array("filter" => array("type" => "input"));   
        $Cardfinancial[]=array("filter" => array("type" => "input"));  


        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input"));
        $Cardfinancial[]=array("filter" => array("type" => "input")); 

        $Cardfee = array();

        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));        
        $Cardfee[]=array("filter" => array("type" => "input"));        
        $Cardfee[]=array("filter" => array("type" => "input"));        
        $Cardfee[]=array("filter" => array("type" => "input"));        
        $Cardfee[]=array("filter" => array("type" => "input")); 
        $Cardfee[]=array("filter" => array("type" => "input")); 

        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));
        $Cardfee[]=array("filter" => array("type" => "input"));

        return View::make('dailybalanceshift/index')
               ->with('filterColumn', $filterColumn)
               ->with('Cardfinancial', $Cardfinancial)
               ->with('Cardfee', $Cardfee)
               ->with("TransactionDrop",$TransactionDrop);    	
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
        if( ( Input::has('start_date') && Input::has('end_date') ) || Input::has('pan') ){
            $result = Dailybalanceshift::getDatatableData($params);
        }

        $rows = array();
        foreach ($result['data'] as $data) {
            $actions = '';
        $actions = "<button type='button' class='btn  btn-sm bg-blue' onclick='openPopupRelateData(\"".$data->id."\",\"".$data->pan."\")'><span class='fa fa-list'></span> </button>";
            
            $nestedData = array();
            $nestedData['actions'] = $actions;
            $nestedData['repot_date'] = $data->repot_date;
            $nestedData['pan'] = $data->pan;
            $nestedData['opening_ac_bal'] = number_format($data->opening_ac_bal,2, '.', '');
            $nestedData['ATM_Settled'] = number_format($data->ATM_Settled,2, '.', '');
            $nestedData['POS_Settled'] = number_format($data->POS_Settled,2, '.', '');
            $nestedData['ATM_FEE'] = number_format($data->ATM_FEE,2, '.', '');
            $nestedData['FPIN'] = number_format($data->FPIN,2, '.', '');
            $nestedData['Bacs_IN'] = number_format($data->Bacs_IN,2, '.', '');
            $nestedData['FP_out'] =number_format($data->FP_out, 2, '.', '');
            $nestedData['AB_DD'] =number_format($data->AB_DD, 2, '.', '');
            $nestedData['FP_out_fee'] = number_format($data->FP_out_fee,2, '.', '');
            $nestedData['charge_backs'] = number_format($data->charge_backs,2, '.', '');
            $nestedData['representments'] = number_format($data->representments,2, '.', '');
            $nestedData['Other_fees'] = number_format($data->Other_fees,2, '.', '');
            $nestedData['Load_Unload'] = number_format($data->Load_Unload,2, '.', '');
            $nestedData['Blocked_Amount'] = number_format($data->Blocked_Amount,2, '.', '');
            $nestedData['Offline_Term_Trans'] = number_format($data->Offline_Term_Trans,2, '.', '');
            $nestedData['Balance_Adj'] = number_format($data->Balance_Adj,2, '.', '');
            $nestedData['closing_ac_bal_calc'] = number_format($data->closing_ac_bal_calc,2, '.', '');
            $nestedData['closing_ac_bal_gps'] = number_format($data->closing_ac_bal_gps,2, '.', '');
            $nestedData['trans_settled_not_adj_gps'] = number_format($data->trans_settled_not_adj_gps,2, '.', '');
            $nestedData['trans_settled_not_adj_gps_2'] = number_format($data->trans_settled_not_adj_gps_2,2, '.', '');
            $nestedData['diff'] = number_format($data->diff,2, '.', '');                
            $rows[] = $nestedData; 

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function getRegenerateDailyBalanceShift()
    {
        return View::make('dailybalanceshift/import');
    }

    public function postRegenerateDailyBalanceShift(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'previous_date' => 'date|before_or_equal:start_date',         
            'start_date' => 'required',         
        ]);
        
        if ($validator->fails()) {
            // $error = $validator->messages()->toJson();
            $error = $validator->errors()->first();            
            return redirect()->to("dailybalanceshift/recalculate")->with("error",$error);                
        }
        
        $this->reCalculateDailyBalanceShift($request->get('start_date'),$request->get('previous_date'));
        return redirect()->to("dailybalanceshift/recalculate")->with("success","Record re-calculated");  
    }

    public function generateDailyBalanceShift()
    {
        $reportDate = "2017-11-17";
        $this->calculateDailyBalanceShift($reportDate);
        echo "success";exit;
    }

    public function reCalculateDailyBalanceShift($reportDate,$previousDate)
    {
        $startDate = $reportDate;
        $endDate = $reportDate;
        $currentPrevDay = $previousDate;

        $startDate = date("Y-m-d",strtotime("+1 day",strtotime($previousDate)));

        // $dayName = date("D",strtotime($reportDate));
        // $previousDate = "";

        // if($dayName == 'Mon')
        //     $previousDate = date("Y-m-d",strtotime("-3 day",strtotime($reportDate)));
        // else
        //     $previousDate = date("Y-m-d",strtotime("-1 day",strtotime($reportDate)));

        // $currentPrevDay = date("Y-m-d",strtotime("-1 day",strtotime($reportDate)));
        

        // if($dayName == 'Mon')
        // {
        //     $startDate = date("Y-m-d",strtotime("-2 day",strtotime($reportDate)));
        // }
        $this->callStoredProcedure($previousDate,$startDate,$endDate,$reportDate,$currentPrevDay);
         Dailybalcardfinancialint::join("daily_balance_shift","daily_balance_shift.id","=","daily_balance_shift_id")->where("repot_date",$reportDate)->delete();

        // DB::table('cardfinancial as c1')->join('daily_balance_shift as d2', 'd2.id', '=', 'c1.daily_balance_shift_id')->where("d2.repot_date",$reportDate)->update(['c1.daily_balance_shift_id' => NULL]);

        // Cardfinancial::join("daily_balance_shift","daily_balance_shift.id" , "=" , "cardfinancial.daily_balance_shift_id")->where("daily_balance_shift.repot_date",$reportDate)->update(["cardfinancial.daily_balance_shift_id"=>NULL , "cardfinancial.updated_at"=>date("Y-m-d- H:i:s")]);

         // Cardfee::join("daily_balance_shift","daily_balance_shift.id" , "=" , "cardfee.daily_balance_shift_id")->where("daily_balance_shift.repot_date",$reportDate)->update(["daily_balance_shift_id"=>NULL]);

    }

    public function calculateDailyBalanceShift($reportDate)
    {
        $dayName = date("D",strtotime($reportDate));
        $previousDate = "";

        if($dayName == 'Mon')
            $previousDate = date("Y-m-d",strtotime("-3 day",strtotime($reportDate)));
        else
            $previousDate = date("Y-m-d",strtotime("-1 day",strtotime($reportDate)));

        $currentPrevDay = date("Y-m-d",strtotime("-1 day",strtotime($reportDate)));
        $startDate = $reportDate;
        $endDate = $reportDate;

        if($dayName == 'Mon')
        {
            $startDate = date("Y-m-d",strtotime("-2 day",strtotime($reportDate)));
        }
        
        $this->callStoredProcedure($previousDate,$startDate,$endDate,$reportDate,$currentPrevDay);
        Dailybalcardfinancialint::join("daily_balance_shift","daily_balance_shift.id","=","daily_balance_shift_id")->where("repot_date",$reportDate)->delete();


        // DB::table('cardfinancial as c1')->join('daily_balance_shift as d2', 'd2.id', '=', 'c1.daily_balance_shift_id')->where("d2.repot_date",$reportDate)->update(['c1.daily_balance_shift_id' => NULL]);

        // Cardfinancial::join("daily_balance_shift","daily_balance_shift.id" , "=" , "cardfinancial.daily_balance_shift_id")->where("daily_balance_shift.repot_date",$reportDate)->update(["cardfinancial.daily_balance_shift_id"=>NULL , "cardfinancial.updated_at"=>date("Y-m-d- H:i:s")]);

         // Cardfee::join("daily_balance_shift","daily_balance_shift.id" , "=" , "cardfee.daily_balance_shift_id")->where("daily_balance_shift.repot_date",$reportDate)->update(["daily_balance_shift_id"=>NULL]);

    }

    private function callStoredProcedure($previousDate,$startDate,$endDate,$reportDate,$currentPrevDay)
    {
        $procedureSet = DB::select("CALL `dailyBalanceShift`('$reportDate','$previousDate','$startDate','$endDate','$currentPrevDay');");
    }

    private function calculateCallDataByProcedure(&$dailyBalanceShiftObj , $procedureCall)
    {
        $dailyBalanceShiftObj->repot_date = $procedureCall->repot_date;
        $dailyBalanceShiftObj->pan = $procedureCall->pan;
        $dailyBalanceShiftObj->opening_ac_bal = round($procedureCall->opening_ac_bal,2);
        $dailyBalanceShiftObj->ATM_Settled = round($procedureCall->ATM_Settled,2);
        $dailyBalanceShiftObj->POS_Settled = round($procedureCall->POS_Settled,2);
        $dailyBalanceShiftObj->ATM_FEE = round($procedureCall->ATM_FEE,2);
        $dailyBalanceShiftObj->FPIN = round($procedureCall->FPIN,2);
        $dailyBalanceShiftObj->FP_out = round($procedureCall->FP_out,2);
        $dailyBalanceShiftObj->FP_out_fee = round($procedureCall->FP_out_fee,2);
        $dailyBalanceShiftObj->Other_fees = round($procedureCall->Other_fees,2);
        $dailyBalanceShiftObj->Load_Unload = round($procedureCall->Load_Unload,2);
        $dailyBalanceShiftObj->Balance_Adj = round($procedureCall->Balance_Adj,2);
        $dailyBalanceShiftObj->closing_ac_bal_calc = $dailyBalanceShiftObj->opening_ac_bal - $dailyBalanceShiftObj->ATM_Settled - $dailyBalanceShiftObj->POS_Settled - $dailyBalanceShiftObj->ATM_FEE + $dailyBalanceShiftObj->FPIN - $dailyBalanceShiftObj->FP_out - $dailyBalanceShiftObj->FP_out_fee - $dailyBalanceShiftObj->Other_fees + $dailyBalanceShiftObj->Load_Unload + $dailyBalanceShiftObj->Balance_Adj ;

        $dailyBalanceShiftObj->closing_ac_bal_gps = $procedureCall->closing_ac_bal_gps;

        if(($dailyBalanceShiftObj->closing_ac_bal_gps - $dailyBalanceShiftObj->closing_ac_bal_calc) < 0)
        {
            $dailyBalanceShiftObj->trans_settled_not_adj_gps = ($dailyBalanceShiftObj->closing_ac_bal_gps - $dailyBalanceShiftObj->closing_ac_bal_calc);
        }
        
        if(($dailyBalanceShiftObj->closing_ac_bal_gps - $dailyBalanceShiftObj->closing_ac_bal_calc) > 0)
        {
            $dailyBalanceShiftObj->trans_settled_not_adj_gps_2 = ($dailyBalanceShiftObj->closing_ac_bal_gps - $dailyBalanceShiftObj->closing_ac_bal_calc);
        }

        $dailyBalanceShiftObj->diff = $dailyBalanceShiftObj->closing_ac_bal_calc - ($dailyBalanceShiftObj->closing_ac_bal_gps + $dailyBalanceShiftObj->trans_settled_not_adj_gps + $dailyBalanceShiftObj->trans_settled_not_adj_gps_2);


    }

    public function filterTransaction(Request $request)
    {
        if(!empty($request->get('TransactionDrop')) && !empty($request->get('txn_start_date')) && !empty($request->get('txn_end_date')))
        {
            $filterData = array();
            $filterData['TransactionDrop'] = $request->get('TransactionDrop');
            $filterData['txn_start_date'] = $request->get('txn_start_date');
            $filterData['txn_end_date'] = $request->get('txn_end_date');

            session(['dailyBalShifttxnfilter'=>$filterData]);

            if($request->get('TransactionDrop') == "Cardfinancial")
            {
                return View::make('dailybalanceshift/cardfinancial')->with('dailybalanceshiftId',$request->get('dailybalanceshiftId'))->with('dailyBalanceShiftPanNum',$request->get('dailyBalanceShiftPanNum'));
            }
            else if($request->get('TransactionDrop') == "Cardfee")
            {
                return View::make('dailybalanceshift/cardfee')->with('dailybalanceshiftId',$request->get('dailybalanceshiftId'))->with('dailyBalanceShiftPanNum',$request->get('dailyBalanceShiftPanNum'));
            }
        }
    }

    public function getCardfinancialDatatable(Request $request)
    {

        $fData = $request->session()->get('dailyBalShifttxnfilter');

        $params = array();
        $params['dailybalanceshiftId'] = $request->get('dailybalanceshiftId');
        $params['dailyBalanceShiftPanNum'] = $request->get('dailyBalanceShiftPanNum');
        if(!empty($fData['txn_start_date']) ){
            $params['start_date'] = $this->dateFormat($fData['txn_start_date'],0);    
        }
        if(!empty($fData['txn_end_date'])){
            $params['end_date'] = $this->dateFormat($fData['txn_end_date'],0);    
        }
        $result = Cardfinancial::getDatatableDataforLink($params);

        $rows = array();
        foreach ($result['data'] as $data) {

            $actions = '';
            $checkDataLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$params['dailybalanceshiftId'])->where("cardfinancial_id",$data->ids)->where("type","cardfinancial")->first();
            if(empty($checkDataLink))
            {
                $actions = "<button type='button' class='btn  btn-sm bg-blue' onclick='linktxnData(\"Cardfinancial\",\"".$data->ids."\")'><span class='fa fa-list'></span> </button>";
            }
            else
            {
                $actions = "<button type='button' class='btn  btn-sm bg-red' onclick='removelinktxnData1(\"Cardfinancial\",\"".$data->ids."\")'><span class='fa fa-remove'></span> </button>";   
            }

            $rows[] = array(
                'actions' => $actions,
                'RecordType'    => e($data->RecordType),                
                'FinId'   => e($data->FinId),
                'AuthId'  => e($data->AuthId),                
                'LocalDate'  => e($data->LocalDate),
                'SettlementDate'  => e($data->SettlementDate),                
                'Card_PAN'    => e($data->Card_PAN),                
                'TxnCode_direction'  => e($data->TxnCode_direction),                                           
                'TxnCode_Type'  => e($data->TxnCode_Type),                                           
                'TxnCode_ProcCode'  => e($data->TxnCode_ProcCode),                                           
                'TxnAmt_value'  => round(e($data->TxnAmt_value),2),                  
                'CashbackAmt_value'  => round(e($data->CashbackAmt_value),2),                          
                'BillAmt_value'  => round(e($data->BillAmt_value),2),             
                'Fee_direction'  => e($data->Fee_direction),                                           
                'Fee_value'  => round(e($data->Fee_value),2),
                'SettlementAmt_value'  => round(e($data->SettlementAmt_value),2),                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),            

                "file_name"=> e($data->file_name),
                "ApprCode"=> e($data->ApprCode),
                "MerchCode"=> e($data->MerchCode),
                "Schema"=> e($data->Schema),
                "ARN"=> e($data->ARN),
                "FIID"=> e($data->FIID),
                "RIID"=> e($data->RIID),
                "ReasonCode"=> e($data->ReasonCode),
                "Card_productid"=> e($data->Card_productid),
                "Card_product"=> e($data->Card_product),
                "Card_programid"=> e($data->Card_programid),
                "Card_branchcode"=> e($data->Card_branchcode),
                "Account_no"=> e($data->Account_no),
                "Account_type"=> e($data->Account_type),
                "TxnCode_Group"=> e($data->TxnCode_Group),
                "TxnAmt_currency"=> e($data->TxnAmt_currency),
                "CashbackAmt_currency"=> e($data->CashbackAmt_currency),
                "BillAmt_currency"=> e($data->BillAmt_currency),
                "BillAmt_rate"=> e($data->BillAmt_rate),
                "Trace_auditno"=> e($data->Trace_auditno),
                "Trace_origauditno"=> e($data->Trace_origauditno),
                "Trace_Retrefno"=> e($data->Trace_Retrefno),
                "Term_code"=> e($data->Term_code),
                "Term_location"=> e($data->Term_location),
                "Term_street"=> e($data->Term_street),
                "Term_city"=> e($data->Term_city),
                "Term_country"=> e($data->Term_country),
                "Term_inputcapability"=> e($data->Term_inputcapability),
                "Term_authcapability"=> e($data->Term_authcapability),
                "Txn_cardholderpresent"=> e($data->Txn_cardholderpresent),
                "Txn_cardpresent"=> e($data->Txn_cardpresent),
                "Txn_cardinputmethod"=> e($data->Txn_cardinputmethod),
                "Txn_cardauthmethod"=> e($data->Txn_cardauthmethod),
                "Txn_cardauthentity"=> e($data->Txn_cardauthentity),
                "Txn_TVR"=> e($data->Txn_TVR),
                "MsgSource_value"=> e($data->MsgSource_value),
                "MsgSource_domesticMaestro"=> e($data->MsgSource_domesticMaestro),
                "Fee_currency"=> e($data->Fee_currency),
                "SettlementAmt_currency"=> e($data->SettlementAmt_currency),
                "SettlementAmt_rate"=> e($data->SettlementAmt_rate),
                "SettlementAmt_date"=> e($data->SettlementAmt_date),
                "Classification_RCC"=> e($data->Classification_RCC),
                "Classification_MCC"=> e($data->Classification_MCC),
                "Response_approved"=> e($data->Response_approved),
                "Response_actioncode"=> e($data->Response_actioncode),
                "Response_responsecode"=> e($data->Response_responsecode),
                "OrigTxnAmt_value"=> e($data->OrigTxnAmt_value),
                "OrigTxnAmt_currency"=> e($data->OrigTxnAmt_currency),
                "OrigTxnAmt_origItemId"=> e($data->OrigTxnAmt_origItemId),
                "OrigTxnAmt_partial"=> e($data->OrigTxnAmt_partial),
                "CCAAmount_value"=> e($data->CCAAmount_value),
                "CCAAmount_currency"=> e($data->CCAAmount_currency),
                "CCAAmount_included"=> e($data->CCAAmount_included),                               
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows); 
    }

    public function getCardfeeDatatable(Request $request)
    {
        $fData = $request->session()->get('dailyBalShifttxnfilter');
        $params = array();
        $params['dailybalanceshiftId'] = $request->get('dailybalanceshiftId');
        $params['dailyBalanceShiftPanNum'] = $request->get('dailyBalanceShiftPanNum');
        if(!empty($fData['txn_start_date']) ){
            $params['start_date'] = $this->dateFormat($fData['txn_start_date'],0);    
        }
        if(!empty($fData['txn_end_date'])){
            $params['end_date'] = $this->dateFormat($fData['txn_end_date'],0);    
        }

        $result = Cardfee::getDatatableDataforLink($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $actions = '';
            $checkDataLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$params['dailybalanceshiftId'])->where("cardfinancial_id",$data->ids)->where("type","cardfee")->first();

            if(empty($checkDataLink))
            {
                $actions = "<button type='button' class='btn  btn-sm bg-blue' onclick='linktxnData(\"Cardfee\",\"".$data->ids."\")'><span class='fa fa-list'></span> </button>";
            }
            else
            {
                $actions = "<button type='button' class='btn  btn-sm bg-red' onclick='removelinktxnData1(\"Cardfee\",\"".$data->ids."\")'><span class='fa fa-remove'></span> </button>";   
            }

            $rows[] = array(
                 'actions' => $actions,
                'CardFeeId'    => e($data->CardFeeId),                
                'SettlementDate'   => e($data->SettlementDate),
                'TxId'  => e($data->TxId),                
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->Desc.'">'. $data->Desc .'</span>',          
                'Card_PAN'  => e($data->Card_PAN),                
                'Account_no'    => e($data->Account_no),                
                'TxnCode_direction'  => e($data->TxnCode_direction),                                           
                'TxnCode_ProcCode'  => e($data->TxnCode_ProcCode),                                           
                'FeeClass_interchangeTransaction'  => e($data->FeeClass_interchangeTransaction),                                           
                'FeeAmt_direction'  => e($data->FeeAmt_direction),                                           
                'FeeAmt_value'  => round(e($data->FeeAmt_value),2),                          
                'Amt_direction'  => e($data->Amt_direction),                                           
                'Amt_value'  => round(e($data->Amt_value),2),                                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),       

                "file_name"  => e($data->file_name),
                "LoadUnloadId"  => e($data->LoadUnloadId),
                "LocalDate"  => e($data->LocalDate),
                "MerchCode"  => e($data->MerchCode),
                "ReasonCode"  => e($data->ReasonCode),
                "FIID"  => e($data->FIID),
                "Card_productid"  => e($data->Card_productid),
                "Card_product"  => e($data->Card_product),
                "Card_programid"  => e($data->Card_programid),
                "Card_branchcode"  => e($data->Card_branchcode),
                "Account_type"  => e($data->Account_type),
                "TxnCode_Type"  => e($data->TxnCode_Type),
                "TxnCode_Group"  => e($data->TxnCode_Group),
                "MsgSource_value"  => e($data->MsgSource_value),
                "MsgSource_domesticMaestro"  => e($data->MsgSource_domesticMaestro),
                "FeeClass_type"  => e($data->FeeClass_type),
                "FeeClass_code"  => e($data->FeeClass_code),
                "FeeAmt_currency"  => e($data->FeeAmt_currency),
                "Amt_currency"  => e($data->Amt_currency),
                                                    
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows); 
    }

    public function linkTxnData(Request $request)
    {
        if($request->get('dailyBalanceShiftId') != '' && $request->get('cardType') != '' && $request->get('ids') != '')
        {
            $dailyBalObj = Dailybalanceshift::where("id",$request->get('dailyBalanceShiftId'))->first();

            if($request->get('cardType') == 'Cardfinancial')
            {
                $cardFinanLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfinancial")->first();

                $cardfinancialObj = Cardfinancial::where("id",$request->get('ids'))->first();
                if(empty($cardFinanLink))
                {
                    if($cardfinancialObj->RecordType == "ADV")
                    {
                        if($cardfinancialObj->TxnCode_direction == "credit")
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfinancialObj->BillAmt_value;
                        }
                        else
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfinancialObj->BillAmt_value;
                        }
                    }
                    else
                    {
                        if($cardfinancialObj->TxnCode_direction == "credit")
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfinancialObj->BillAmt_value;
                        }
                        else
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfinancialObj->BillAmt_value;
                        }
                    }
                    
                    // $cardfinancialObj->daily_balance_shift_id = $request->get('dailyBalanceShiftId');

                    // $cardfinancialObj->save();

                    $objInst = new Dailybalcardfinancialint();
                    $objInst->daily_balance_shift_id = $request->get('dailyBalanceShiftId');
                    $objInst->cardfinancial_id = $request->get('ids');
                    $objInst->type = "cardfinancial";

                    $objInst->save();


                }
                
            }
            else
            {
                $cardFinanLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfee")->first();

                $cardfeeObj = Cardfee::where("id",$request->get('ids'))->first();
                if(empty($cardFinanLink))
                {
                    if($cardfeeObj->TxnCode_direction == "credit")
                    {
                        $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfeeObj->Amt_value;
                    }
                    else
                    {
                        $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfeeObj->Amt_value;
                    }

                    // $cardfeeObj->daily_balance_shift_id = $request->get('dailyBalanceShiftId');

                    // $cardfeeObj->save();

                    $objInst = new Dailybalcardfinancialint();
                    $objInst->daily_balance_shift_id = $request->get('dailyBalanceShiftId');
                    $objInst->cardfinancial_id = $request->get('ids');
                    $objInst->type = "cardfee";

                    $objInst->save();

                }
                
            }

            $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc + $dailyBalObj->trans_settled_not_adj_gps + $dailyBalObj->trans_settled_not_adj_gps_2 - $dailyBalObj->closing_ac_bal_gps;

            // if($dailyBalObj->closing_ac_bal_gps > $dailyBalObj->closing_ac_bal_calc)
            // {
            //     $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc - ($dailyBalObj->closing_ac_bal_gps - abs($dailyBalObj->trans_settled_not_adj_gps) - abs($dailyBalObj->trans_settled_not_adj_gps_2) );
            // }
            // else
            // {
            //     $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc - ($dailyBalObj->closing_ac_bal_gps + abs($dailyBalObj->trans_settled_not_adj_gps) + abs($dailyBalObj->trans_settled_not_adj_gps_2) );
            // }

            $dailyBalObj->save();

            echo "success";
        }

        exit;
        
    }

    public function removelinktxnData(Request $request)
    {
        if($request->get('dailyBalanceShiftId') != '' && $request->get('cardType') != '' && $request->get('ids') != '')
        {
            $dailyBalObj = Dailybalanceshift::where("id",$request->get('dailyBalanceShiftId'))->first();

            if($request->get('cardType') == 'Cardfinancial')
            {
                $cardFinanLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfinancial")->first();

                $cardfinancialObj = Cardfinancial::where("id",$request->get('ids'))->first();
                if(!empty($cardFinanLink))
                {
                    if($cardfinancialObj->RecordType == "ADV")
                    {
                        if($cardfinancialObj->TxnCode_direction == "credit")
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfinancialObj->BillAmt_value;
                        }
                        else
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfinancialObj->BillAmt_value;
                        }
                    }
                    else
                    {
                        if($cardfinancialObj->TxnCode_direction == "credit")
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfinancialObj->BillAmt_value;
                        }
                        else
                        {
                            $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfinancialObj->BillAmt_value;
                        }
                    }
                    
                    // $cardfinancialObj->daily_balance_shift_id = NULL;

                    // $cardfinancialObj->save();

                    Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfinancial")->delete();

                }
                
            }
            else
            {
                $cardFinanLink = Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfee")->first();

                $cardfeeObj = Cardfee::where("id",$request->get('ids'))->first();

                if($cardfeeObj->TxnCode_direction == "credit")
                {
                    $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  + $cardfeeObj->Amt_value;
                }
                else
                {
                    $dailyBalObj->trans_settled_not_adj_gps_2  = $dailyBalObj->trans_settled_not_adj_gps_2  - $cardfeeObj->Amt_value;
                }

                // $cardfeeObj->daily_balance_shift_id = NULL;

                // $cardfeeObj->save();

                Dailybalcardfinancialint::where("daily_balance_shift_id",$request->get('dailyBalanceShiftId'))->where("cardfinancial_id",$request->get('ids'))->where("type","cardfee")->delete();
                
            }

            $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc + $dailyBalObj->trans_settled_not_adj_gps + $dailyBalObj->trans_settled_not_adj_gps_2 - $dailyBalObj->closing_ac_bal_gps;
            
            // if($dailyBalObj->closing_ac_bal_gps > $dailyBalObj->closing_ac_bal_calc)
            // {
            //     $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc - ($dailyBalObj->closing_ac_bal_gps - abs($dailyBalObj->trans_settled_not_adj_gps) - abs($dailyBalObj->trans_settled_not_adj_gps_2) );
            // }
            // else
            // {
            //     $dailyBalObj->diff = $dailyBalObj->closing_ac_bal_calc - ($dailyBalObj->closing_ac_bal_gps + abs($dailyBalObj->trans_settled_not_adj_gps) + abs($dailyBalObj->trans_settled_not_adj_gps_2) );
            // }

            $dailyBalObj->save();
            echo "success";

        }

        exit;
        
    }


}
