<?php
namespace App\Http\Controllers\Mainrecoreportdaily;

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
use App\Models\Bankstatement;
use App\Models\Bankbalance;
use App\Models\Settelementsummary;
use App\Models\Rejactedbacs;
use App\Models\Directdebits;
use App\Models\Dailybalanceshift;

use App\Models\Mainrecoreportdaily;
/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class MainrecoreportdailyController extends Controller
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

        return View::make('mainrecodaily/index')->with('filterColumn', $filterColumn); 
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
            $result = Mainrecoreportdaily::getDatatableData($params);
        }

        $rows = array();
        foreach ($result['data'] as $data) {

            $nestedData = array();
            $nestedData['report_date'] = $data->report_date;
            $nestedData['diff_amt'] = number_format($data->diff_amt,2, '.', '');
            $nestedData['not_loaded_card_opening'] = number_format($data->not_loaded_card_opening,2, '.', '');
            $nestedData['ab_declined_not_loaded'] = number_format($data->ab_declined_not_loaded,2, '.', '');
            $nestedData['not_matched_to_load'] = number_format($data->not_matched_to_load,2, '.', '');
            $nestedData['bacs_not_loaded_card'] = number_format($data->bacs_not_loaded_card,2, '.', '');
            $nestedData['dca_adjustment'] =number_format($data->dca_adjustment, 2, '.', '');
            $nestedData['not_loaded_card_closing'] = number_format($data->not_loaded_card_closing,2, '.', '');
            $nestedData['app_fp_in_not_rec_current'] = number_format($data->app_fp_in_not_rec_current,2, '.', '');
            $nestedData['app_fp_in_not_rec_closing'] = number_format($data->app_fp_in_not_rec_closing,2, '.', '');
            $nestedData['remain_fp_out_current'] = number_format($data->remain_fp_out_current,2, '.', '');
            $nestedData['remain_fp_out_closing'] = number_format($data->remain_fp_out_closing,2, '.', '');
            $nestedData['bank_debits_not_match_current'] = number_format($data->bank_debits_not_match_current,2, '.', '');
            $nestedData['bank_debits_not_match_closing'] = number_format($data->bank_debits_not_match_closing,2, '.', '');
            $nestedData['unmatch_bacs_ddr_current'] = number_format($data->unmatch_bacs_ddr_current,2, '.', '');
            $nestedData['unmatch_bacs_ddr_closing'] = number_format($data->unmatch_bacs_ddr_closing,2, '.', '');
            $nestedData['unauthorized_dd_current'] = number_format($data->unauthorized_dd_current,2, '.', '');
            $nestedData['unauthorized_dd_recovered'] = number_format($data->unauthorized_dd_recovered,2, '.', '');
            $nestedData['unauthorized_dd_closing'] = number_format($data->unauthorized_dd_closing,2, '.', '');
            $nestedData['balance_adj_current'] = number_format($data->balance_adj_current,2, '.', '');
            $nestedData['balance_adj_closing'] = number_format($data->balance_adj_closing,2, '.', '');
            $nestedData['charge_back_current'] = number_format($data->charge_back_current,2, '.', '');
            $nestedData['charge_back_bal'] = number_format($data->charge_back_bal,2, '.', '');
            $nestedData['return_to_source_adj'] = number_format($data->return_to_source_adj,2, '.', '');
            $nestedData['return_to_source_fp_out_sent'] = number_format($data->return_to_source_fp_out_sent,2, '.', '');
            $nestedData['return_to_source_bal'] = number_format($data->return_to_source_bal,2, '.', '');
            $nestedData['blocked_amt_pos'] = number_format($data->blocked_amt_pos,2, '.', '');
            $nestedData['blocked_amt_atm'] = number_format($data->blocked_amt_atm,2, '.', '');
            $nestedData['blocked_amt_ofline_tt'] = number_format($data->blocked_amt_ofline_tt,2, '.', '');
            $nestedData['blocked_amt_fee'] = number_format($data->blocked_amt_fee,2, '.', '');
            $nestedData['blocked_amt_closing'] = number_format($data->blocked_amt_closing,2, '.', '');
            $nestedData['net_diff'] = number_format($data->net_diff,2, '.', '');
            $nestedData['pos_interchang_current'] = number_format($data->pos_interchang_current,2, '.', '');
            $nestedData['pos_interchang_closing'] = number_format($data->pos_interchang_closing,2, '.', '');
            $nestedData['atm_interchang_current'] = number_format($data->atm_interchang_current,2, '.', '');
            $nestedData['atm_interchang_closing'] = number_format($data->atm_interchang_closing,2, '.', '');
            $nestedData['fp_out_fee'] = number_format($data->fp_out_fee,2, '.', '');
            $nestedData['atm_fee'] = number_format($data->atm_fee,2, '.', '');
            $nestedData['others_fee'] = number_format($data->others_fee,2, '.', '');
            $nestedData['closing_fee'] = number_format($data->closing_fee,2, '.', '');
            $nestedData['txn_sattled_not_adj_curr'] = number_format($data->txn_sattled_not_adj_curr,2, '.', '');
            $nestedData['txn_sattled_not_adj_closing'] = number_format($data->txn_sattled_not_adj_closing,2, '.', '');
            $nestedData['adj_from_phy_vir_current'] = number_format($data->adj_from_phy_vir_current,2, '.', '');
            $nestedData['adj_from_phy_vir_closing'] = number_format($data->adj_from_phy_vir_closing,2, '.', '');
            $nestedData['missing_gps_bal_current'] = number_format($data->missing_gps_bal_current,2, '.', '');
            $nestedData['missing_gps_bal_closing'] = number_format($data->missing_gps_bal_closing,2, '.', '');
            $nestedData['bank_charges_current'] = number_format($data->bank_charges_current,2, '.', '');
            $nestedData['bank_charges_closing'] = number_format($data->bank_charges_closing,2, '.', '');
            $nestedData['ultra_net'] = number_format($data->ultra_net,2, '.', '');
            $rows[] = $nestedData; 

        }

        return array('total'=>$result['count'], 'rows'=>$rows); 

    }

    public function getRegenerateMainRecoDaily()
    {
        return View::make('mainrecodaily/regenmainreco');
    }

    public function postRegenerateMainRecoDaily(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',         
        ]);
        
        if ($validator->fails()) {
            // $error = $validator->messages()->toJson();
            $error = $validator->errors()->first();            
            return redirect()->to("mainrecodaily/recalculate")->with("error",$error);                
        }

        $this->calculateMainRecoDaily($request->get('start_date'));
        return redirect()->to("mainrecodaily/recalculate")->with("success","Record re-calculated");  
    }

    public function generateMainRecoDaily()
    {
        $reportDate = "2017-11-16";
        $this->calculateMainRecoDaily($reportDate);
        echo "success";exit;
    }

    public function calculateMainRecoDaily($reportDate)
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

        $mainRecoObj = Mainrecoreportdaily::where("report_date",$reportDate)->first();

        $uniqueId = Helper::generateUniqueId();

        if(empty($mainRecoObj))
        {
            $mainRecoObj = new Mainrecoreportdaily();
            $mainRecoObj->id = $uniqueId;
        }

        $mainRecoObj->report_date = $reportDate;

       
        $this->calculateCallData($mainRecoObj ,$reportDate ,$previousDate ,$startDate ,$endDate );    

        $mainRecoObj->save();
    }

    private function calculateCallData(&$mainRecoObj ,$reportDate ,$previousDate ,$startDate ,$endDate)
    {
        $mainRecoObj->blocked_amt_closing = 0 ; // leaveblank
        $mainRecoObj->adj_from_phy_vir_closing = 0 ; // leaveblank
        $mainRecoObj->bank_debits_not_match_closing = 0 ; // logic need to discuss with sir
        $mainRecoObj->missing_gps_bal_closing = 0 ; // logic need to discuss with sir

        $previousRecoData = $this->getPreviousDateMainRecoData($previousDate);
        $sattelmentSummaryData =  $this->sattelmentSummaryData($reportDate);

        if(!empty($sattelmentSummaryData))
        {
            $mainRecoObj->diff_amt =$sattelmentSummaryData->overall_cash_position;
            $mainRecoObj->pos_interchang_current = ($sattelmentSummaryData->value_of_pos_interchange * -1);
            $mainRecoObj->atm_interchang_current = ($sattelmentSummaryData->value_of_atm_interchange * -1);
        }


        $mainRecoObj->not_loaded_card_opening = $this->mainRecoNotLoadedCardOpening($previousDate);
        $mainRecoObj->ab_declined_not_loaded = $this->mainRecoAbDeclinedNotLoaded($reportDate);
        $mainRecoObj->not_matched_to_load = $this->mainRecoNotMatchedToLoad($reportDate);
        $mainRecoObj->bacs_not_loaded_card = $this->mainRecoBacsNotLoadedCard($startDate,$endDate);
        $mainRecoObj->dca_adjustment = $this->mainRecoDcaAdjustment($reportDate);
        $mainRecoObj->not_loaded_card_closing = $mainRecoObj->not_loaded_card_opening + $mainRecoObj->ab_declined_not_loaded + $mainRecoObj->not_matched_to_load + $mainRecoObj->bacs_not_loaded_card + $mainRecoObj->dca_adjustment;
        $mainRecoObj->app_fp_in_not_rec_current = $this->mainRecoFpInNotRecCurrent($reportDate , $previousDate);

        $mainRecoObj->remain_fp_out_current = $this->mainRecoRemainFpOutCurrent($reportDate , $previousDate);

        $mainRecoObj->unauthorized_dd_current = $this->mainRecoUnautorizedDDCurrent($reportDate);
        $mainRecoObj->balance_adj_current = $this->mainRecoBalanceAdjCurrent($startDate,$endDate);
        $mainRecoObj->charge_back_current = $this->mainRecoChargeBackCurrent($startDate,$endDate);

        $mainRecoObj->return_to_source_adj = $this->mainRecoReturnToSourceAdj($startDate,$endDate);
        $mainRecoObj->return_to_source_fp_out_sent = $this->mainRecoRtsFpOutSent($reportDate);
        
        $mainRecoObj->fp_out_fee = $this->mainRecoFpOutFee($startDate , $endDate);
        $mainRecoObj->atm_fee = $this->mainRecoAtmFee($startDate , $endDate);
        $mainRecoObj->others_fee = $this->mainRecoOthersFee($startDate , $endDate);
        $mainRecoObj->txn_sattled_not_adj_curr = $this->mainRecoTxnSattledNotAdjCurr($reportDate);
        $mainRecoObj->bank_charges_current = $this->mainRecoBankChargesCurrent($reportDate);
        
        if(!empty($previousRecoData))
        {
            $mainRecoObj->app_fp_in_not_rec_closing = $previousRecoData->app_fp_in_not_rec_closing + $mainRecoObj->app_fp_in_not_rec_current;

            $mainRecoObj->unauthorized_dd_closing = $previousRecoData->unauthorized_dd_closing + $mainRecoObj->unauthorized_dd_current;
            $mainRecoObj->balance_adj_closing = $previousRecoData->balance_adj_closing + $mainRecoObj->balance_adj_current;
            $mainRecoObj->charge_back_bal = $previousRecoData->charge_back_bal + $mainRecoObj->charge_back_current;

            $mainRecoObj->return_to_source_bal = $previousRecoData->return_to_source_bal + $mainRecoObj->return_to_source_adj - $mainRecoObj->return_to_source_fp_out_sent;
            
            $mainRecoObj->pos_interchang_closing = $previousRecoData->pos_interchang_closing + $mainRecoObj->pos_interchang_current;
            $mainRecoObj->atm_interchang_closing = $previousRecoData->atm_interchang_closing + $mainRecoObj->atm_interchang_current;

            $mainRecoObj->closing_fee = $previousRecoData->closing_fee + $mainRecoObj->fp_out_fee + $mainRecoObj->atm_fee + $mainRecoObj->others_fee;

            $mainRecoObj->txn_sattled_not_adj_closing = $previousRecoData->txn_sattled_not_adj_closing + $mainRecoObj->txn_sattled_not_adj_curr;
            $mainRecoObj->bank_charges_closing = $previousRecoData->bank_charges_closing + $mainRecoObj->bank_charges_current;

            $mainRecoObj->remain_fp_out_closing = $previousRecoData->remain_fp_out_closing + $mainRecoObj->remain_fp_out_current;
        }
        else
        {
            $mainRecoObj->app_fp_in_not_rec_closing =$mainRecoObj->app_fp_in_not_rec_current;
            $mainRecoObj->unauthorized_dd_closing = $mainRecoObj->unauthorized_dd_current;
            $mainRecoObj->balance_adj_closing = $mainRecoObj->balance_adj_current;
            $mainRecoObj->charge_back_bal = $mainRecoObj->charge_back_current;
            $mainRecoObj->return_to_source_bal = $mainRecoObj->return_to_source_adj - $mainRecoObj->return_to_source_fp_out_sent;

            $mainRecoObj->pos_interchang_closing = $mainRecoObj->pos_interchang_current;
            $mainRecoObj->atm_interchang_closing = $mainRecoObj->atm_interchang_current;
            $mainRecoObj->closing_fee = $mainRecoObj->fp_out_fee + $mainRecoObj->atm_fee + $mainRecoObj->others_fee;
            $mainRecoObj->txn_sattled_not_adj_closing = $mainRecoObj->txn_sattled_not_adj_curr;
            $mainRecoObj->bank_charges_closing = $mainRecoObj->bank_charges_current;
            $mainRecoObj->remain_fp_out_closing = $mainRecoObj->remain_fp_out_current;
        }
        
        $mainRecoObj->net_diff = $mainRecoObj->diff_amt - $mainRecoObj->not_loaded_card_closing  - $mainRecoObj->remain_fp_out_closing + $mainRecoObj->balance_adj_closing - $mainRecoObj->blocked_amt_closing + $mainRecoObj->app_fp_in_not_rec_closing - $mainRecoObj->bank_debits_not_match_closing ;

        
        $mainRecoObj->ultra_net = $mainRecoObj->net_diff - $mainRecoObj->pos_interchang_closing - $mainRecoObj->atm_interchang_closing - $mainRecoObj->closing_fee - $mainRecoObj->adj_from_phy_vir_closing + $mainRecoObj->txn_sattled_not_adj_closing - $mainRecoObj->missing_gps_bal_closing + $mainRecoObj->bank_charges_closing;

    }

    private function getPreviousDateMainRecoData($previousDate)
    {
        $data = Mainrecoreportdaily::where("report_date",$previousDate)->first();

        return $data;
    }

    private function sattelmentSummaryData($reportDate)
    {
        $data = Settelementsummary::where("settlement_date",$reportDate)->first();

        return $data;
       
    }

    private function mainRecoNotLoadedCardOpening($previousDate)
    {
        $data = Mainrecoreportdaily::select("not_loaded_card_closing")->where("report_date",$previousDate)->first();

        if(!empty($data))
        {
            return $data->not_loaded_card_closing;
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoAbDeclinedNotLoaded($reportDate)
    {
        $whereDt = "BACSDDR";
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("reco_flg","Y")->whereRaw("REPLACE(description,' ','') = '$whereDt' ")->first();

        $data_result = 0;
        if(!empty($data))
        {
            $data_result = $data->credit_value - $data->debit_value;
        }

        $data1 = Fpout::select(DB::raw('SUM(Amount) as Amount_value'))->whereRaw("ReferenceInformation","rtn")->where("file_date",$reportDate)->first();

        $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$reportDate)->where("CashCode_direction","credit")->where("banking_type","Declined")->where("reco_date",$reportDate)->first();

        $resultValue = 0;

        $resultValue = $data2->BillAmt_value + $data_result - $data1->Amount_value;

        return $resultValue;
    }

    private function mainRecoNotMatchedToLoad($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("reco_flg","N")->first();

        if(!empty($data))
        {
            $resultValue = $data->credit_value - $data->debit_value;
            return $resultValue;
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoBacsNotLoadedCard($startDate,$endDate)
    {
        $data = Rejactedbacs::select(DB::raw('SUM(Txn_Amt) as Txn_Amt_value'))->whereBetween("Date",[$startDate ,$endDate])->first();

        if(!empty($data->Txn_Amt_value))
        {
            return $data->Txn_Amt_value;
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoDcaAdjustment($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("type","DCA Adjustment")->first();

        if(!empty($data))
        {
            $resultValue = $data->credit_value - $data->debit_value;
            return $resultValue;
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoFpInNotRecCurrent($reportDate , $previousDate)
    {
        $data1 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$reportDate)->where("CashType","RCP")->where("banking_type","Approved")
                ->where(function($query) use($reportDate) {
                    $query->where("reco_flg","N");
                    $query->orWhere("reco_date" ,"!=" , $reportDate);
                })->first();

        $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$previousDate)->where("CashType","RCP")->where("banking_type","Approved")->where("reco_date",$reportDate)->first();

        $resultValue = $data1->BillAmt_value - $data2->BillAmt_value;

        return $resultValue;
    }

    
    private function mainRecoRemainFpOutCurrent($reportDate)
    {
        $data1 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$reportDate)->where("CashType","PAY")->where("banking_type","Approved")->where("fp_out_date","!=",$reportDate)->first();

        $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate","!=",$reportDate)->where("CashType","PAY")->where("banking_type","Approved")->where("fp_out_date",$reportDate)->first();

        $resultValue = $data1->BillAmt_value - $data2->BillAmt_value;

        return $resultValue;
    }



    private function mainRecoUnautorizedDDCurrent($reportDate)
    {
        $data = Directdebits::select(DB::raw('SUM(amount) as amount'))->where("Due_Date",$reportDate)->first();

        return $data->amount;

    }

    private function mainRecoBalanceAdjCurrent($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","credit")->where("Desc","NOT LIKE","%chargeback%")->first();

        return $data->Amount_value;
    }

    private function mainRecoChargeBackCurrent($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","credit")->where("Desc","LIKE","%chargeback%")->first();

        return $data->Amount_value;
    }

    private function mainRecoReturnToSourceAdj($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","debit")->first();

        return $data->Amount_value;
    }

    private function mainRecoRtsFpOutSent($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("description","LIKE","%tfr%")->where("description","NOT LIKE","%charges%")->first();

        $resultValue = $data->credit_value - $data->debit_value;
        return ($resultValue * -1);
    }

    private function mainRecoFpOutFee($startDate , $endDate)
    {
        $data = Agencybanking::select(DB::raw('SUM(Fee_value) as Fee_value'))->where("banking_type","Approved")->where("CashType","PAY")->whereBetween("SettlementDate",[$startDate , $endDate])->first();

        return $data->Fee_value;
    }

    private function mainRecoAtmFee($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->where("Desc","Domestic Fee")->where("Amt_direction","debit")->whereBetween("SettlementDate",[$startDate , $endDate])->first();
        return $data->Amt_value;
    }

    private function mainRecoOthersFee($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate , $endDate])
            ->where(function($query){
                $query->whereIn("TxnCode_ProcCode",["999999","084999"]);
                $query->orWhere("Desc","Forex Fee");
        })->first();

        return $data->Amt_value;
    }

    private function mainRecoTxnSattledNotAdjCurr($reportDate)
    {
        $data1 = Dailybalanceshift::select(DB::raw('SUM(trans_settled_not_adj_gps) as trans_settled_not_adj_gps'))->where("repot_date",$reportDate)->first();

        $data2 = Dailybalanceshift::select(DB::raw('SUM(trans_settled_not_adj_gps_2) as trans_settled_not_adj_gps_2'))->where("repot_date",$reportDate)->first();

        $resultValue = $data1->trans_settled_not_adj_gps + $data2->trans_settled_not_adj_gps_2;

        return $resultValue;
    }

    private function mainRecoBankChargesCurrent($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("description","LIKE","%charges%")->first();

        $resultValue = $data->credit_value - $data->debit_value;
        return $resultValue;
    }

}
