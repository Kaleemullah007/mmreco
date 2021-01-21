<?php
namespace App\Http\Controllers\Settelementsummary;

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

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class SettelementsummaryController extends Controller
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

        return View::make('settelementsummary/index')->with('filterColumn', $filterColumn); 
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
        // if( Input::has('start_date') && Input::has('end_date') ){
            $result = Settelementsummary::getDatatableData($params);
        // }
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $nestedData['settlement_date'] = $data->settlement_date;
            $nestedData['opening_ac_bal'] = number_format($data->opening_ac_bal,2, '.', '');
            $nestedData['scheme_to_settlement_transfer'] = number_format($data->scheme_to_settlement_transfer,2, '.', '');
            $nestedData['charges'] = number_format($data->charges,2, '.', '');
            $nestedData['deposits_into_settlement_ac'] = number_format($data->deposits_into_settlement_ac,2, '.', '');
            $nestedData['monthly_interest_settlement_ac'] = number_format($data->monthly_interest_settlement_ac,2, '.', '');
            $nestedData['no_of_pos_txn'] = round($data->no_of_pos_txn,2);
            $nestedData['value_of_pos_txn'] = number_format($data->value_of_pos_txn,2, '.', '');
            $nestedData['value_of_pos_interchange'] = number_format($data->value_of_pos_interchange,2, '.', '');
            $nestedData['total_value_of_pos_txn'] = number_format($data->total_value_of_pos_txn,2, '.', '');
            $nestedData['number_of_atm_txn'] = round($data->number_of_atm_txn,2);
            $nestedData['value_of_atm_txn'] = number_format($data->value_of_atm_txn,2, '.', '');
            $nestedData['value_of_atm_interchange'] = number_format($data->value_of_atm_interchange,2, '.', '');
            $nestedData['total_value_of_atm_txn'] = number_format($data->total_value_of_atm_txn,2, '.', '');
            $nestedData['total_value_of_txn_settled'] = number_format($data->total_value_of_txn_settled,2, '.', '');
            $nestedData['settlement_closing_bal_adj'] = number_format($data->settlement_closing_bal_adj,2, '.', '');
            $nestedData['closing_ac_bal'] = number_format($data->closing_ac_bal,2, '.', '');
            $nestedData['scheme_closing_bal'] = number_format($data->scheme_closing_bal,2, '.', '');
            $nestedData['dr_cr_bank'] = number_format($data->dr_cr_bank,2, '.', '');
            $nestedData['prefund'] = number_format($data->prefund,2, '.', '');
            $nestedData['total_bal_available_to_cust_bal'] = number_format($data->total_bal_available_to_cust_bal,2, '.', '');
            $nestedData['available_cust_bal_credit'] = number_format($data->available_cust_bal_credit,2, '.', '');
            $nestedData['available_cust_bal_debit'] = number_format($data->available_cust_bal_debit,2, '.', '');
            $nestedData['overall_cash_position'] = number_format($data->overall_cash_position,2, '.', '');
            $nestedData['live_pans'] = round($data->live_pans,2);
            $nestedData['transactional_fees'] = number_format($data->transactional_fees,2, '.', '');
            $nestedData['month'] = $data->month;
            $rows[] = $nestedData; 

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function getRegenerateSettelemaneSummary()
    {
        return View::make('settelementsummary/import');
    }

    public function postRegenerateSettelemaneSummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',         
        ]);
        
        if ($validator->fails()) {
            // $error = $validator->messages()->toJson();
            $error = $validator->errors()->first();            
            return redirect()->to("settelementsummary/recalculate")->with("error",$error);                
        }

        $this->calculateSettelementSummary($request->get('start_date'));
        return redirect()->to("settelementsummary/recalculate")->with("success","Record re-calculated");  
    }

    public function generateSettelementSummary()
    {
        $reportDate = "2017-11-20";
        $this->calculateSettelementSummary($reportDate);
        echo "success";exit;
    }

    public function calculateSettelementSummary($reportDate)
    {
        $dayName = date("D",strtotime($reportDate));
        $previousDate = "";

        if($dayName == 'Mon')
            $previousDate = date("Y-m-d",strtotime("-3 day",strtotime($reportDate)));
        else
            $previousDate = date("Y-m-d",strtotime("-1 day",strtotime($reportDate)));

        $startDate = $reportDate;
        $endDate = $reportDate;

        if($dayName == 'Mon')
        {
            $startDate = date("Y-m-d",strtotime("-2 day",strtotime($reportDate)));
        }

        $settlementSummaryObj = Settelementsummary::where("settlement_date",$reportDate)->first();

        $uniqueId = Helper::generateUniqueId();

        if(empty($settlementSummaryObj))
        {
            $settlementSummaryObj = new Settelementsummary();
            $settlementSummaryObj->id = $uniqueId;
        }

        $settlementSummaryObj->settlement_date = $reportDate;

        $settlementSummaryObj->scheme_to_settlement_transfer = $this->bankStatementFlgCalculation($reportDate,"SKIMTOSATTEL");
       
        $procedureCall = $this->callStoredProcedure($previousDate,$startDate,$endDate,$reportDate);
        $this->calculateCallDataByProcedure($settlementSummaryObj,$procedureCall);    

        $settlementSummaryObj->month = date("M-Y",strtotime($reportDate));

        $settlementSummaryObj->save();
    }

    private function bankStatementFlgCalculation($reportDate , $flg)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("extra_flags",$flg)->first();

        $resultValue = $data->credit_value - $data->debit_value;
        return $resultValue;
    }

    private function callStoredProcedure($previousDate,$startDate,$endDate,$reportDate)
    {
        $procedureSet = DB::select("CALL `settlementSummary`('$previousDate','$startDate','$endDate','$reportDate', @p4, @p5, @p6, @p7, @p8, @p9, @p10, @p11, @p12, @p13, @p14, @p15, @p16, @p17, @p18);");
        $procedureCall = DB::select("SELECT @p4 AS `getOpeningAcBal`, @p5 AS `noOfPostTxn`, @p6 AS `valueOfPosTxn1`, @p7 AS `valueOfPosTxn2`, @p8 AS `valueOfPosTxn3`, @p9 AS `valueOfPosInterchange1`, @p10 AS `valueOfPosInterchange2`, @p11 AS `numberOfAtmTxn`, @p12 AS `valueOfAtmTxn`, @p13 AS `valueOfAtmInterchange`, @p14 AS `closingAcBal`, @p15 AS `schemeClosingBal`, @p16 AS `availableCustBalCredit`, @p17 AS `availableCustBalDebit`, @p18 AS `livePans`;");

        return $procedureCall;
    }

    private function calculateCallDataByProcedure(&$settlementSummaryObj , $procedureCall)
    {

        $settlementSummaryObj->opening_ac_bal = round($procedureCall[0]->getOpeningAcBal,2);
        $settlementSummaryObj->no_of_pos_txn = $procedureCall[0]->noOfPostTxn;

        $valueOfPosTxn = $procedureCall[0]->valueOfPosTxn1 + $procedureCall[0]->valueOfPosTxn2 - $procedureCall[0]->valueOfPosTxn3;
        $settlementSummaryObj->value_of_pos_txn = round($valueOfPosTxn,2);

        $valueOfPosInterchange = $procedureCall[0]->valueOfPosInterchange2 - $procedureCall[0]->valueOfPosInterchange1;
        $settlementSummaryObj->value_of_pos_interchange = round($valueOfPosInterchange,2);

        $settlementSummaryObj->total_value_of_pos_txn = round(($settlementSummaryObj->value_of_pos_txn + $settlementSummaryObj->value_of_pos_interchange),2);

        $settlementSummaryObj->number_of_atm_txn = $procedureCall[0]->numberOfAtmTxn;
        $settlementSummaryObj->value_of_atm_txn = round($procedureCall[0]->valueOfAtmTxn,2);
        $settlementSummaryObj->value_of_atm_interchange = round($procedureCall[0]->valueOfAtmInterchange,2);

        $settlementSummaryObj->total_value_of_atm_txn = round(($settlementSummaryObj->value_of_atm_txn + $settlementSummaryObj->value_of_atm_interchange),2);

        $settlementSummaryObj->total_value_of_txn_settled = round(($settlementSummaryObj->total_value_of_pos_txn + $settlementSummaryObj->total_value_of_atm_txn),2);

        $settlementSummaryObj->closing_ac_bal = round(($procedureCall[0]->closingAcBal - $settlementSummaryObj->total_value_of_txn_settled - $settlementSummaryObj->scheme_to_settlement_transfer),2);

        $settlementSummaryObj->scheme_closing_bal = round($procedureCall[0]->schemeClosingBal,2);

        $settlementSummaryObj->total_bal_available_to_cust_bal = round(($settlementSummaryObj->closing_ac_bal + $settlementSummaryObj->scheme_closing_bal),2);

        $settlementSummaryObj->available_cust_bal_credit = round($procedureCall[0]->availableCustBalCredit,2);
        $settlementSummaryObj->available_cust_bal_debit = round($procedureCall[0]->availableCustBalDebit,2);
        $settlementSummaryObj->overall_cash_position = round(($settlementSummaryObj->total_bal_available_to_cust_bal - $settlementSummaryObj->available_cust_bal_credit - $settlementSummaryObj->available_cust_bal_debit),2);
        $settlementSummaryObj->live_pans = round($procedureCall[0]->livePans,2);

    }

    // private function  getOpeningAcBal($reportDate , $previousDate)
    // {
    //     $data = Settelementsummary::select("closing_ac_bal")->where("settlement_date",$previousDate)->first();

    //     if(!empty($data))
    //     {
    //         return $data->closing_ac_bal;
    //     }
    //     else
    //     {
    //         return 0;
    //     }
    // }

    // private function noOfPostTxn($startDate , $endDate)
    // {
    //     $data = Cardfinancial::whereBetween("SettlementDate",[$startDate,$endDate])->whereIn("TxnCode_Type",['pos','pos_cb','pos_re'])->count();

    //     return $data;
    // }

    // private function valueOfPosTxn($startDate , $endDate)
    // {
    //     $dataVal1 = Cardfinancial::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->whereIn("TxnCode_Type",['pos','pos_cb'])->where("TxnCode_direction","debit")->first();



    //     $dataVal2 = Cardfinancial::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->whereIn("TxnCode_Type",['pos','pos_cb'])->where("TxnCode_direction","credit")->first();

    //     $dataVal3 = Cardfinancial::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->whereIn("TxnCode_Type",['pos_re'])->first();


    //     $resultVal = 0;

    //     $resultVal = $dataVal1->BillAmt_value - $dataVal2->BillAmt_value - $dataVal3->BillAmt_value;

    //     return $resultVal;
    // }
  
    // private function valueOfPosInterchange($startDate , $endDate)
    // {
    //     $dataVal1 = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("Desc","Interchange Fee")->where("Amt_direction","credit")->first();

    //     $dataVal2 = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("Desc","Interchange Fee")->where("Amt_direction","debit")->where("TxnCode_ProcCode","200000")->first();

    //     $resultVal = 0;
    //     $resultVal = $dataVal2->Amt_value - $dataVal1->Amt_value;
    //     return $resultVal;
    // }

    // private function numberOfAtmTxn($startDate , $endDate)
    // {
    //     $data = Cardfinancial::whereBetween("SettlementDate",[$startDate,$endDate])->whereIn("TxnCode_Type",['atm'])->count();

    //     return $data;
    // }

    // private function valueOfAtmTxn($startDate , $endDate)
    // {
    //     $dataVal1 = Cardfinancial::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type",'atm')->first();

    //     $resultVal = 0;

    //     $resultVal = $dataVal1->BillAmt_value;

    //     return $resultVal;
    // }
  
    // private function valueOfAtmInterchange($startDate , $endDate)
    // {
    //     $dataVal1 = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("Desc","Interchange Fee")->where("Amt_direction","debit")->where("TxnCode_ProcCode","010000")->first();

    //     $resultVal = 0;
    //     $resultVal = $dataVal1->Amt_value;

    //     return $resultVal;
    // }   

    // private function  closingAcBal($objData , $previousDate)
    // {
    //     $data = Settelementsummary::select("closing_ac_bal")->where("settlement_date",$previousDate)->first();

    //     if(!empty($data))
    //     {
    //         return ($data->closing_ac_bal - $objData->total_value_of_txn_settled);
    //     }
    //     else
    //     {
    //         return (0 - $objData->total_value_of_txn_settled);
    //     }
    // }
    
    // private function schemeClosingBal($reportDate)
    // {
    //     $data =  Bankstatement::select("bal")->where("date",$reportDate)->whereNotNull("bal")->first();

    //     if(!empty($data))
    //     {
    //         return $data->bal;
    //     }
    //     else
    //     {
    //         return 0;
    //     }
    // }

    // private function availableCustBalCredit($reportDate)
    // {
    //     $data = Bankbalance::select(DB::raw('SUM(bank_balance.finamt) as finamt'))
    //             ->join("bank_balance_card","bank_balance_card.bank_balance_id","=","bank_balance.id")
    //             ->where("bank_balance.bankbal_date",$reportDate)
    //             ->where("bank_balance_card.primary","Y")
    //             ->whereNull("bank_balance_card.deleted_at")
    //             ->where("bank_balance.finamt",">",0)
    //             ->first();
    //     return $data->finamt;
    // }

    // private function availableCustBalDebit($reportDate)
    // {
    //     $data = Bankbalance::select(DB::raw('SUM(bank_balance.finamt) as finamt'))
    //             ->join("bank_balance_card","bank_balance_card.bank_balance_id","=","bank_balance.id")
    //             ->where("bank_balance.bankbal_date",$reportDate)
    //             ->where("bank_balance_card.primary","Y")
    //             ->whereNull("bank_balance_card.deleted_at")
    //             ->where("bank_balance.finamt","<",0)
    //             ->first();
    //     return $data->finamt;
    // }

    // private function livePans($reportDate)
    // {
    //     $data = Bankbalance::select(DB::raw('count(bank_balance.id) as cnt'))
    //             ->join("bank_balance_card","bank_balance_card.bank_balance_id","=","bank_balance.id")
    //             ->where("bank_balance.bankbal_date",$reportDate)
    //             ->where("bank_balance_card.statcode","00")
    //             ->whereNull("bank_balance_card.deleted_at")
    //             ->first();
    //     return $data->cnt;
    // }
}
