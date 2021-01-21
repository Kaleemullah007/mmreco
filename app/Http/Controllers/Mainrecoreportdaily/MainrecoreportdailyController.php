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
use App\Models\Fpout;
use App\Models\Advice;

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
        
        // $filterColumn[]=array("filter" => array("type" => "input"));
        // $filterColumn[]=array("filter" => array("type" => "input"));
        // $filterColumn[]=array("filter" => array("type" => "input"));
        // $filterColumn[]=array("filter" => array("type" => "input"));
        // $filterColumn[]=array("filter" => array("type" => "input"));

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

            $nestedData['opening_unclaim_fund'] = number_format($data->opening_unclaim_fund,2, '.', '');
            $nestedData['abd_opening'] = number_format($data->abd_opening,2, '.', '');
            $nestedData['abd_fpreceived'] = number_format($data->abd_fpreceived,2, '.', '');
            $nestedData['abd_fpreturn'] = number_format($data->abd_fpreturn,2, '.', '');
            $nestedData['abd_bounceback'] = number_format($data->abd_bounceback,2, '.', '');
            $nestedData['abd_bacsreceived'] = number_format($data->abd_bacsreceived, 2, '.', '');
            $nestedData['abd_bacsreturn'] = number_format($data->abd_bacsreturn,2, '.', '');
            $nestedData['abd_closing'] = number_format($data->abd_closing,2, '.', '');
            $nestedData['nmtl_fpinopening'] = number_format($data->nmtl_fpinopening,2, '.', '');
            $nestedData['nmtl_fpinreceived'] = number_format($data->nmtl_fpinreceived,2, '.', '');
            $nestedData['nmtl_fpinreturn'] = number_format($data->nmtl_fpinreturn,2, '.', '');
            $nestedData['nmtl_fpinclosing'] = number_format($data->nmtl_fpinclosing,2, '.', '');
            $nestedData['nmtl_bacsopening'] = number_format($data->nmtl_bacsopening,2, '.', '');
            $nestedData['nmtl_bacsreceived'] = number_format($data->nmtl_bacsreceived,2, '.', '');
            $nestedData['nmtl_bacsreturn'] = number_format($data->nmtl_bacsreturn,2, '.', '');
            $nestedData['nmtl_bounceback'] = number_format($data->nmtl_bounceback,2, '.', '');
            $nestedData['nmtl_bacsclosing'] = number_format($data->nmtl_bacsclosing,2, '.', '');
            $nestedData['dcaadj_opening'] = number_format($data->dcaadj_opening,2, '.', '');
            $nestedData['dcaadj_dcaadj'] = number_format($data->dcaadj_dcaadj,2, '.', '');
            $nestedData['dcaadj_adjtocard'] = number_format($data->dcaadj_adjtocard,2, '.', '');
            $nestedData['dcaadj_closing'] = number_format($data->dcaadj_closing,2, '.', '');
            $nestedData['closing_unclaim_fund'] = number_format($data->closing_unclaim_fund,2, '.', '');
            $nestedData['fprp_opening'] = number_format($data->fprp_opening,2, '.', '');
            $nestedData['fprp_ppreceived'] = number_format($data->fprp_ppreceived,2, '.', '');
            $nestedData['fprp_cdpipeline'] = number_format($data->fprp_cdpipeline,2, '.', '');
            $nestedData['fprp_closing'] = number_format($data->fprp_closing,2, '.', '');
            $nestedData['fpop_opening'] = number_format($data->fpop_opening,2, '.', '');
            $nestedData['fpop_pppaid'] = number_format($data->fpop_pppaid,2, '.', '');
            $nestedData['fpop_cdtrans'] = number_format($data->fpop_cdtrans,2, '.', '');
            $nestedData['fpop_closing'] = number_format($data->fpop_closing,2, '.', '');
            $nestedData['umbd_opening'] = number_format($data->umbd_opening,2, '.', '');
            $nestedData['umbd_curr'] = number_format($data->umbd_curr,2, '.', '');
            $nestedData['umbd_adj'] = number_format($data->umbd_adj,2, '.', '');
            $nestedData['umbd_closing'] = number_format($data->umbd_closing,2, '.', '');
            $nestedData['unmatch_bacs_ddr_current'] = number_format($data->unmatch_bacs_ddr_current,2, '.', '');
            $nestedData['unmatch_bacs_ddr_adj'] = number_format($data->unmatch_bacs_ddr_adj,2, '.', '');
            $nestedData['unmatch_bacs_ddr_closing'] = number_format($data->unmatch_bacs_ddr_closing,2, '.', '');
            $nestedData['unauthorized_dd_opening'] = number_format($data->unauthorized_dd_opening,2, '.', '');
            $nestedData['unauthorized_dd_current'] = number_format($data->unauthorized_dd_current,2, '.', '');
            $nestedData['unauthorized_dd_recovered'] = number_format($data->unauthorized_dd_recovered,2, '.', '');
            $nestedData['unauthorized_dd_closing'] = number_format($data->unauthorized_dd_closing,2, '.', '');
            $nestedData['dec_dd_not_cr_opening'] = number_format($data->dec_dd_not_cr_opening,2, '.', '');
            $nestedData['dec_dd_not_cr_uncr'] = number_format($data->dec_dd_not_cr_uncr,2, '.', '');
            $nestedData['dec_dd_not_cr_returned'] = number_format($data->dec_dd_not_cr_returned,2, '.', '');
            $nestedData['dec_dd_not_cr_closing'] = number_format($data->dec_dd_not_cr_closing,2, '.', '');
            $nestedData['missing_dd_opening'] = number_format($data->missing_dd_opening,2, '.', '');
            $nestedData['missing_dd_unknown'] = number_format($data->missing_dd_unknown,2, '.', '');
            $nestedData['missing_dd_returned'] = number_format($data->missing_dd_returned,2, '.', '');
            $nestedData['missing_dd_closing'] = number_format($data->missing_dd_closing,2, '.', '');
            $nestedData['dd_closing_total'] = number_format($data->dd_closing_total,2, '.', '');
            $nestedData['balance_adj_opening'] = number_format($data->balance_adj_opening,2, '.', '');
            $nestedData['balance_adj_credits'] = number_format($data->balance_adj_credits,2, '.', '');
            $nestedData['balance_adj_debits'] = number_format($data->balance_adj_debits,2, '.', '');
            $nestedData['balance_adj_closing'] = number_format($data->balance_adj_closing,2, '.', '');
            $nestedData['charge_back_opening'] = number_format($data->charge_back_opening, 2, '.', '');
            $nestedData['charge_back_credits'] = number_format($data->charge_back_credits,2, '.', '');
            $nestedData['charge_back_debits'] = number_format($data->charge_back_debits,2, '.', '');
            $nestedData['charge_back_closing'] = number_format($data->charge_back_closing,2, '.', '');
            $nestedData['return_to_source_opening'] = number_format($data->return_to_source_opening,2, '.', '');
            $nestedData['return_to_source_adj'] = number_format($data->return_to_source_adj,2, '.', '');
            $nestedData['return_to_source_fp_out_sent'] = number_format($data->return_to_source_fp_out_sent,2, '.', '');
            $nestedData['return_to_source_closing'] = number_format($data->return_to_source_closing,2, '.', '');
            $nestedData['blocked_amt_pos'] = number_format($data->blocked_amt_pos,2, '.', '');
            $nestedData['blocked_amt_atm'] = number_format($data->blocked_amt_atm,2, '.', '');
            $nestedData['blocked_amt_ofline_tt'] = number_format($data->blocked_amt_ofline_tt,2, '.', '');
            $nestedData['blocked_amt_fee'] = number_format($data->blocked_amt_fee,2, '.', '');
            $nestedData['blocked_amt_closing'] = number_format($data->blocked_amt_closing,2, '.', '');
            $nestedData['pos_interchang_opening'] = number_format($data->pos_interchang_opening,2, '.', '');
            $nestedData['pos_interchang_pos_dr'] = number_format($data->pos_interchang_pos_dr,2, '.', '');
            // $nestedData['pos_interchang_pos_cb'] = number_format($data->pos_interchang_pos_cb,2, '.', '');
            // $nestedData['pos_interchang_pos_re'] = number_format($data->pos_interchang_pos_re,2, '.', '');
            // $nestedData['pos_interchang_pos_cr'] = number_format($data->pos_interchang_pos_cr,2, '.', '');
            // $nestedData['pos_interchang_pos_chargeback'] = number_format($data->pos_interchang_pos_chargeback,2, '.', '');
            // $nestedData['pos_interchang_pos_repres'] = number_format($data->pos_interchang_pos_repres,2, '.', '');
            $nestedData['pos_interchang_closing'] = number_format($data->pos_interchang_closing,2, '.', '');
            $nestedData['atm_interchang_opening'] = number_format($data->atm_interchang_opening,2, '.', '');
            $nestedData['atm_interchang_current'] = number_format($data->atm_interchang_current,2, '.', '');
            $nestedData['atm_interchang_closing'] = number_format($data->atm_interchang_closing,2, '.', '');
            $nestedData['fp_out_fee'] = number_format($data->fp_out_fee,2, '.', '');
            $nestedData['atm_fee'] = number_format($data->atm_fee,2, '.', '');
            $nestedData['forex_fee'] = number_format($data->forex_fee,2, '.', '');
            $nestedData['card_replace_fee'] = number_format($data->card_replace_fee,2, '.', '');
            $nestedData['code_999999_fee'] = number_format($data->code_999999_fee,2, '.', '');
            $nestedData['closing_fee'] = number_format($data->closing_fee,2, '.', '');
            $nestedData['txn_sattled_not_adj_opening'] = number_format($data->txn_sattled_not_adj_opening,2, '.', '');
            $nestedData['txn_sattled_not_adj_curr'] = number_format($data->txn_sattled_not_adj_curr,2, '.', '');
            $nestedData['txn_sattled_not_adj_prev'] = number_format($data->txn_sattled_not_adj_prev,2, '.', '');
            $nestedData['txn_sattled_not_adj_closing'] = number_format($data->txn_sattled_not_adj_closing,2, '.', '');
            $nestedData['adj_from_phy_vir_current'] = number_format($data->adj_from_phy_vir_current,2, '.', '');
            $nestedData['adj_from_phy_vir_closing'] = number_format($data->adj_from_phy_vir_closing,2, '.', '');
            $nestedData['missing_gps_bal_current'] = number_format($data->missing_gps_bal_current,2, '.', '');
            $nestedData['missing_gps_bal_closing'] = number_format($data->missing_gps_bal_closing,2, '.', '');
            $nestedData['bank_charges_current'] = number_format($data->bank_charges_current,2, '.', '');
            $nestedData['bank_charges_closing'] = number_format($data->bank_charges_closing,2, '.', '');
            $nestedData['interst_current'] = number_format($data->interst_current,2, '.', '');
            $nestedData['interest_closing'] = number_format($data->interest_closing,2, '.', '');


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

        $previousRecoData = $this->getPreviousDateMainRecoData($previousDate);
        $sattelmentSummaryData =  $this->sattelmentSummaryData($reportDate);

        if(!empty($sattelmentSummaryData))
        {
            $mainRecoObj->diff_amt =$sattelmentSummaryData->overall_cash_position;
        }
        
        $mainRecoObj->opening_unclaim_fund = @$previousRecoData->opening_unclaim_fund;

        $mainRecoObj->abd_opening = @$previousRecoData->abd_closing;
        $mainRecoObj->abd_fpreceived = $this->mianRecoAbd_fpreceived($reportDate);
        $abd_fpreturn1 = $this->bankStatementFlgCalculation($reportDate,"ABDFPR");
        $abd_fpreturn2 = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"ABDFPRADJ");
        $abd_fpreturn3 = $this->abd_fpreturn_Calculate($reportDate,$startDate,$endDate);
        $mainRecoObj->abd_fpreturn = $abd_fpreturn1 + $abd_fpreturn2 + $abd_fpreturn3;
        $mainRecoObj->abd_bounceback = $this->mainRecoAbd_bounceback($reportDate);
        $mainRecoObj->abd_bacsreceived = $this->mainRecoAbd_bacsreceived($reportDate);
        
        $abd_bacsreturn1 = $this->bankStatementFlgCalculation($reportDate,"ABDBR");
        $abd_bacsreturn2 = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"ABDBCSRADJ");
        $mainRecoObj->abd_bacsreturn = $abd_bacsreturn1 + $abd_bacsreturn2;

        $mainRecoObj->abd_closing = $mainRecoObj->abd_opening + $mainRecoObj->abd_fpreceived + $mainRecoObj->abd_fpreturn + $mainRecoObj->abd_bounceback + $mainRecoObj->abd_bacsreceived + $mainRecoObj->abd_bacsreturn;


        $mainRecoObj->nmtl_fpinopening = @$previousRecoData->nmtl_fpinclosing;
        $mainRecoObj->nmtl_fpinreceived = $this->bankStatementFlgCalculation($reportDate,"NMTLFPINREC");
        
        $nmtl_fpinreturn1 = $this->bankStatementFlgCalculation($reportDate,"NMTLFPR");
        $nmtl_fpinreturn2 = $this->balanceAdjustmentFlgCalculationfpinreturn($startDate,$endDate,"NLOCFPINRADJ");
        $mainRecoObj->nmtl_fpinreturn = $nmtl_fpinreturn1 + $nmtl_fpinreturn2;
        $mainRecoObj->nmtl_bounceback =  $this->bankStatementFlgCalculation($reportDate,"NMTLBOUNCE"); //$this->nmtl_fpinreturn($reportDate);
        $mainRecoObj->nmtl_fpinclosing = $mainRecoObj->nmtl_fpinopening + $mainRecoObj->nmtl_fpinreceived + $mainRecoObj->nmtl_fpinreturn + $mainRecoObj->nmtl_bounceback;

        $mainRecoObj->nmtl_bacsopening = @$previousRecoData->nmtl_bacsclosing;
        $mainRecoObj->nmtl_bacsreceived = $this->nmtl_bacsreceived($previousDate);
        $nmtl_bacsreturn1 = $this->bankStatementFlgCalculation($reportDate,"NMTLBR"); 
        $nmtl_bacsreturn2 = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"NLOCBACSADJ"); 
        $mainRecoObj->nmtl_bacsreturn = $nmtl_bacsreturn1 + $nmtl_bacsreturn2;
        $mainRecoObj->nmtl_bacsclosing = $mainRecoObj->nmtl_bacsopening + $mainRecoObj->nmtl_bacsreceived + $mainRecoObj->nmtl_bacsreturn;

        $mainRecoObj->dcaadj_opening = @$previousRecoData->dcaadj_closing;
        $mainRecoObj->dcaadj_dcaadj = $this->mainRecoDcaAdjustment($reportDate);
        $mainRecoObj->dcaadj_adjtocard = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"DCAADJ");
        $mainRecoObj->dcaadj_closing = $mainRecoObj->dcaadj_opening + $mainRecoObj->dcaadj_dcaadj + $mainRecoObj->dcaadj_adjtocard;

        $mainRecoObj->closing_unclaim_fund = $mainRecoObj->abd_closing + $mainRecoObj->nmtl_fpinclosing + $mainRecoObj->nmtl_bacsclosing + $mainRecoObj->dcaadj_closing;

        $mainRecoObj->fprp_opening = @$previousRecoData->fprp_closing;
        $mainRecoObj->fprp_ppreceived = $this->mainRecoFprp_ppreceived($reportDate ,$previousDate);
        $fprp_cdpipeline1 = $this->mainRecoFprp_cdpipeline($reportDate ,$previousDate);
        $fprp_cdpipeline2 = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"FPRECPIPCURRENTADJ");
        $mainRecoObj->fprp_cdpipeline = $fprp_cdpipeline1 + $fprp_cdpipeline2;
        $mainRecoObj->fprp_closing = $mainRecoObj->fprp_opening + $mainRecoObj->fprp_ppreceived + $mainRecoObj->fprp_cdpipeline;

        $mainRecoObj->fpop_opening = @$previousRecoData->fpop_closing;
        $mainRecoObj->fpop_pppaid = $this->mainRecoFpop_pppaid($reportDate);
        $fpop_cdtrans1 = $this->mainRecoFpop_cdtrans($reportDate);
        $fpop_cdtrans2 = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"FPOUTPIPCURRENTADJ");
        $fpop_cdtrans3 = $this->mainRecoFpop_cdtrans_unreco($startDate,$endDate);
        $mainRecoObj->fpop_cdtrans = $fpop_cdtrans1 + $fpop_cdtrans2 + $fpop_cdtrans3;
        $mainRecoObj->fpop_closing = $mainRecoObj->fpop_opening + $mainRecoObj->fpop_pppaid + $mainRecoObj->fpop_cdtrans;


        $mainRecoObj->umbd_opening = @$previousRecoData->umbd_closing;
        $mainRecoObj->umbd_curr = $this->bankStatementFlgCalculation($reportDate,"UMBNKDCUR"); 
        $mainRecoObj->umbd_adj = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"UMBNKDADJ"); 
        $mainRecoObj->umbd_closing = $mainRecoObj->umbd_opening + $mainRecoObj->umbd_curr + $mainRecoObj->umbd_adj;

        $mainRecoObj->unmatch_bacs_ddr_current = $this->bankStatementFlgCalculation($reportDate,"UNBDCUR"); 
        $mainRecoObj->unmatch_bacs_ddr_adj = $this->balanceAdjustmentFlgCalculation($startDate,$endDate,"UNMBDADJ"); 
        $mainRecoObj->unmatch_bacs_ddr_closing = @$previousRecoData->unmatch_bacs_ddr_closing + $mainRecoObj->unmatch_bacs_ddr_current + $mainRecoObj->unmatch_bacs_ddr_adj;

        $mainRecoObj->unauthorized_dd_opening = @$previousRecoData->unauthorized_dd_closing;
        $mainRecoObj->unauthorized_dd_current = $this->mainRecoUnauthorized_dd_current($reportDate);
        $mainRecoObj->unauthorized_dd_recovered = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "UNAUTHDDREC"); 
        $mainRecoObj->unauthorized_dd_closing = $mainRecoObj->unauthorized_dd_opening + $mainRecoObj->unauthorized_dd_current + $mainRecoObj->unauthorized_dd_recovered;

        $mainRecoObj->dec_dd_not_cr_opening = @$previousRecoData->dec_dd_not_cr_closing;
        $mainRecoObj->dec_dd_not_cr_uncr     = $this->dec_dd_not_cr_uncr($reportDate); // set manually not clear
        $mainRecoObj->dec_dd_not_cr_returned = $this->bankStatementFlgCalculation($reportDate,"DECDDNCRTN"); 
        $mainRecoObj->dec_dd_not_cr_closing = $mainRecoObj->dec_dd_not_cr_opening + $mainRecoObj->dec_dd_not_cr_uncr - $mainRecoObj->dec_dd_not_cr_returned;

        $mainRecoObj->missing_dd_opening = @$previousRecoData->missing_dd_closing;
        $mainRecoObj->missing_dd_unknown     = $this->missing_dd_unknown($previousDate);
        $mainRecoObj->missing_dd_returned     = $this->bankStatementFlgCalculation($reportDate,"MISSDDRTN"); 
        $mainRecoObj->missing_dd_closing     = $mainRecoObj->missing_dd_opening + $mainRecoObj->missing_dd_unknown + $mainRecoObj->missing_dd_returned;

        $mainRecoObj->dd_closing_total = $mainRecoObj->unauthorized_dd_closing + $mainRecoObj->dec_dd_not_cr_closing + $mainRecoObj->missing_dd_closing;

        $mainRecoObj->balance_adj_opening = @$previousRecoData->balance_adj_closing;
        $mainRecoObj->balance_adj_credits =  $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "BALADJGENADJ"); //$this->mainRecoBalance_adj_credits($startDate , $endDate);
        $mainRecoObj->balance_adj_debits = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "BALADJGENCONTRA");  //$this->mainRecoBalance_adj_debits($startDate , $endDate);
        $mainRecoObj->balance_adj_closing = $mainRecoObj->balance_adj_opening + $mainRecoObj->balance_adj_credits + $mainRecoObj->balance_adj_debits;

        $mainRecoObj->charge_back_opening = @$previousRecoData->charge_back_closing;
        $mainRecoObj->charge_back_credits = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "CHARGEBACKADJ"); // $this->mainRecoCharge_back_credits($startDate , $endDate);
        $mainRecoObj->charge_back_debits = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "CHARGEBACKRECOVERED"); //$this->mainRecoCharge_back_debits($startDate , $endDate);
        $mainRecoObj->charge_back_closing = $mainRecoObj->charge_back_opening + $mainRecoObj->charge_back_credits + $mainRecoObj->charge_back_debits;

        $mainRecoObj->return_to_source_opening = @$previousRecoData->return_to_source_closing;
        $mainRecoObj->return_to_source_adj = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "RTSADJ"); //$this->mainRecoReturnToSourceAdj($startDate,$endDate);
        $mainRecoObj->return_to_source_fp_out_sent = $this->balanceAdjustmentFlgCalculation($startDate , $endDate , "RTSFPOUT"); //$this->mainRecoRtsFpOutSent($reportDate);
        $mainRecoObj->return_to_source_closing = $mainRecoObj->return_to_source_opening + $mainRecoObj->return_to_source_adj - $mainRecoObj->return_to_source_fp_out_sent;

        $mainRecoObj->blocked_amt_pos = 0 ; // set manually 
        $mainRecoObj->blocked_amt_atm = 0 ; // set manually 
        $mainRecoObj->blocked_amt_ofline_tt = 0 ; // set manually 
        $mainRecoObj->blocked_amt_fee = 0 ; // set manually 
        $mainRecoObj->blocked_amt_closing = $mainRecoObj->blocked_amt_pos + $mainRecoObj->blocked_amt_atm + $mainRecoObj->blocked_amt_ofline_tt + $mainRecoObj->blocked_amt_fee;

        $mainRecoObj->pos_interchang_opening = @$previousRecoData->pos_interchang_closing;

        // $mainRecoObj->pos_interchang_pos_dr = $this->mainRecopos_interchang_pos_dr($startDate , $endDate);
        // $mainRecoObj->pos_interchang_pos_cb = $this->mainRecopos_interchang_pos_cb($startDate , $endDate);
        // $mainRecoObj->pos_interchang_pos_re = $this->mainRecopos_interchang_pos_re($startDate , $endDate);
        // $mainRecoObj->pos_interchang_pos_cr = $this->mainRecopos_interchang_pos_cr($startDate , $endDate);
        // $mainRecoObj->pos_interchang_pos_chargeback =$this->pos_interchang_pos_chargeback($startDate , $endDate);
        // $mainRecoObj->pos_interchang_pos_repres = $this->pos_interchang_pos_repres($startDate , $endDate);

        $mainRecoObj->pos_interchang_pos_dr = $this->mainRecopos_pos_interchange_value($startDate , $endDate);

        $mainRecoObj->pos_interchang_closing = $mainRecoObj->pos_interchang_opening + $mainRecoObj->pos_interchang_pos_dr;

        $mainRecoObj->atm_interchang_opening = @$previousRecoData->atm_interchang_closing;
        
        // if(!empty($sattelmentSummaryData))
        //     $mainRecoObj->atm_interchang_current = ($sattelmentSummaryData->value_of_atm_interchange * -1);
            
        $mainRecoObj->atm_interchang_current = $this->mainRecopos_atm_interchang_current($startDate , $endDate);
        
        $mainRecoObj->atm_interchang_closing = $mainRecoObj->atm_interchang_opening + $mainRecoObj->atm_interchang_current;


        $mainRecoObj->fp_out_fee = $this->mainRecoFpOutFee($startDate , $endDate);
        $mainRecoObj->atm_fee = $this->mainRecoAtmFee($startDate , $endDate);
        $mainRecoObj->forex_fee = $this->mainRecoforex_fee($startDate , $endDate);
        $mainRecoObj->card_replace_fee = 0; // set manually not clear
        $mainRecoObj->code_999999_fee = $this->mainRecocode_999999_fee($startDate , $endDate);

        $mainRecoObj->closing_fee = @$previousRecoData->closing_fee + $mainRecoObj->fp_out_fee + $mainRecoObj->atm_fee + $mainRecoObj->forex_fee + $mainRecoObj->card_replace_fee + $mainRecoObj->code_999999_fee;

        $mainRecoObj->txn_sattled_not_adj_opening = @$previousRecoData->txn_sattled_not_adj_closing;
        $mainRecoObj->txn_sattled_not_adj_curr = $this->mainRecoTxnSattledNotAdjCurr($reportDate);
        $mainRecoObj->txn_sattled_not_adj_prev = $this->txn_sattled_not_adj_prev($reportDate);
        $mainRecoObj->txn_sattled_not_adj_closing = $mainRecoObj->txn_sattled_not_adj_opening + $mainRecoObj->txn_sattled_not_adj_curr + $mainRecoObj->txn_sattled_not_adj_prev;

        $mainRecoObj->adj_from_phy_vir_current = 0; // set Manually
        $mainRecoObj->adj_from_phy_vir_closing = @$previousRecoData->adj_from_phy_vir_closing + $mainRecoObj->adj_from_phy_vir_current; // set Manually

        $mainRecoObj->missing_gps_bal_current = 0; // set Manually
        $mainRecoObj->missing_gps_bal_closing = @$previousRecoData->missing_gps_bal_closing + $mainRecoObj->missing_gps_bal_current; // set Manually

        $mainRecoObj->bank_charges_current =  $this->bankStatementFlgCalculation($reportDate,"MISSDDRTN");
        $mainRecoObj->bank_charges_closing = @$previousRecoData->bank_charges_closing + $mainRecoObj->bank_charges_current;

        $mainRecoObj->interst_current = $this->bankStatementFlgCalculation($reportDate,"INTERSTCUR");
        $mainRecoObj->interest_closing = @$previousRecoData->interest_closing + $mainRecoObj->interst_current;


        
        $mainRecoObj->ultra_net = $mainRecoObj->diff_amt - $mainRecoObj->closing_unclaim_fund - $mainRecoObj->fpop_closing + $mainRecoObj->balance_adj_closing - $mainRecoObj->blocked_amt_closing + $mainRecoObj->fprp_closing - $mainRecoObj->umbd_closing + $mainRecoObj->charge_back_closing + $mainRecoObj->dd_closing_total - $mainRecoObj->return_to_source_closing - $mainRecoObj->unmatch_bacs_ddr_closing - $mainRecoObj->pos_interchang_closing - $mainRecoObj->atm_interchang_closing - $mainRecoObj->closing_fee - $mainRecoObj->adj_from_phy_vir_closing + $mainRecoObj->txn_sattled_not_adj_closing - $mainRecoObj->missing_gps_bal_closing + $mainRecoObj->bank_charges_closing - $mainRecoObj->interest_closing;

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

    private function mianRecoAbd_fpreceived($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(bank_statement.credit) as credit_value') , DB::raw('SUM(bank_statement.debit) as debit_value'))
            ->join("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")
            ->join("agencybanking","agencybanking.id","=","txn_mapping_int.txn_table_id")
            ->where("bank_statement.date",$reportDate)
            ->where("bank_statement.reco_flg","Y")
            ->where("bank_statement.type","!=","Faster Payment Return")
            ->whereNull("bank_statement.extra_flags")
            ->where("agencybanking.CashCode_CashType","fpy")
            ->where("banking_type","Declined")
            ->first();

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

    private function abd_fpreturn_Calculate($reportDate,$startDate,$endDate)
    {
        $data1 = Fpout::select(DB::raw('SUM(Amount) as Amount_value'))
                ->join("agencybanking","agencybanking.id","=","fp_out.agencybanking_Id")
                ->where('fp_out.reco_date',$reportDate)
                ->where("agencybanking.banking_type","=","Declined")
                ->where("fp_out.reco_flg","Y")
                ->first();

        if(!empty($data1->Amount_value))
        {
            return -($data1->Amount_value);
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoAbd_fpreturn($reportDate)
    {
        $data1 = Fpout::select(DB::raw('SUM(Amount) as Amount_value'))->where("ReferenceInformation","rtn")->where("file_date",$reportDate)->first();

        if(!empty($data1->Amount_value))
        {
            return -($data1->Amount_value);
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoAbd_bounceback($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(bank_statement.credit) as credit_value') , DB::raw('SUM(bank_statement.debit) as debit_value'))
            ->join("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")
            ->join("agencybanking","agencybanking.id","=","txn_mapping_int.txn_table_id")
            ->where("bank_statement.date",$reportDate)
            ->where("bank_statement.type","Faster Payment Return")
            ->where("agencybanking.banking_type","Declined")
            ->first();

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

    private function mainRecoAbd_bacsreceived($reportDate)
    {
         $data2 = Agencybanking::select(DB::raw('SUM(CashAmt_value) as CashAmt_value'))->where("SettlementDate",$reportDate)->where("CashCode_CashType","bac")->where("banking_type","Declined")->first();

        return $data2->CashAmt_value;
    }

    private function mainRecoAbd_bacsreturn($reportDate)
    {
        $whereDt = "BACSDDR";
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("reco_flg","Y")->whereNull("bank_statement.extra_flags")->whereRaw("REPLACE(description,' ','') = '$whereDt' ")->first();

        $data_result = 0;
        if(!empty($data))
        {
            $data_result = $data->credit_value - $data->debit_value;
            return $data_result;
        }
        else
        {
            return 0;
        }
    }

    private function mainReconmtl_fpinreceived($reportDate)
    {
         $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("reco_flg","N")->first();

         $data_result = $data->credit_value - $data->debit_value;


         $data1 = Bankstatement::select(DB::raw('SUM(bank_statement.credit) as credit_value') , DB::raw('SUM(bank_statement.debit) as debit_value'))
            ->join("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")
            ->join("agencybanking","agencybanking.id","=","txn_mapping_int.txn_table_id")
            ->where("bank_statement.date",$reportDate)
            ->where("agencybanking.reco_date",$reportDate)
            ->where("agencybanking.SettlementDate",">",$reportDate)
            ->first();

        $data1_result = $data1->credit_value - $data1->debit_value;    
        
        $resultValue = 0;

        $resultValue = $data_result + $data1_result;

        return $resultValue;
    }

    private function nmtl_fpinreturn($reportDate)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("reco_flg","N")->where("type","Faster Payment Return")->first();

        $data_result = $data->credit_value - $data->debit_value;
        return $data_result;
    }

    private function nmtl_bacsreceived($previousDate)
    {
        $data2 = Advice::select(DB::raw('SUM(actual_amount) as actual_amount'))->where("file_date","=",$previousDate)->where("code","99")->whereNull("related_table_id")->first();

        return $data2->actual_amount;
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

    private function mainRecoFprp_cdpipeline($reportDate , $previousDate)
    {
        $data1 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$reportDate)->where("CashType","RCP")->where("banking_type","Approved")
                ->where(function($query) use($reportDate) {
                    $query->where("reco_flg","N");
                    $query->orWhere("reco_date" ,"!=" , $reportDate);
                })->first();

        return  $data1->BillAmt_value ;
    } 

    private function mainRecoFprp_ppreceived($reportDate , $previousDate)
    {
        $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$previousDate)->where("CashType","RCP")->where("banking_type","Approved")->where("reco_date",$reportDate)->first();

        if(!empty($data2->BillAmt_value))
        {
            return -($data2->BillAmt_value);
        }
        else
        {
            return 0;
        }
        
    }

    private function mainRecoFpop_pppaid($reportDate)
    {
        // $data1 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate",$reportDate)->where("CashType","PAY")->where("banking_type","Approved")->where("fp_out_date","!=",$reportDate)->first();

        $data1 = Fpout::select(DB::raw('SUM(Amount) as Amount_value'))
                ->join("agencybanking","agencybanking.id","=","fp_out.agencybanking_Id")
                ->where('fp_out.reco_date',$reportDate)
                ->where("agencybanking.file_date","!=",$reportDate)
                ->where("agencybanking.banking_type","=","Approved")
                ->where("fp_out.reco_flg","Y")
                ->first();

        if(!empty($data1->Amount_value))
        {
            return -($data1->Amount_value);
        }
        else
        {
            return 0;
        }

    }

    private function mainRecoFpop_cdtrans($reportDate)
    {
        // $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))->where("SettlementDate","!=",$reportDate)->where("CashType","PAY")->where("banking_type","Approved")->where("fp_out_date",$reportDate)->first();

        $data1 = Fpout::select(DB::raw('SUM(Amount) as Amount_value'))
                ->join("agencybanking","agencybanking.id","=","fp_out.agencybanking_Id")
                ->where("agencybanking.file_date",$reportDate)
                ->where("agencybanking.banking_type","=","Approved")
                ->where(function($query) use ($reportDate){
                    $query->where('fp_out.reco_date','>',$reportDate);
                    $query->orWhereNull('fp_out.reco_date');
                })
                ->first();
        return $data1->Amount_value;
       
    }

    private function mainRecoFpop_cdtrans_unreco($startDate,$endDate)
    {
        $data2 = Agencybanking::select(DB::raw('SUM(BillAmt_value) as BillAmt_value'))
                ->where("CashCode_CashType","fpy")
                ->where("CashType","PAY")
                ->where("banking_type","Approved")
                ->whereBetween("file_date",[$startDate,$endDate])
                ->where("reco_flg","N")
                ->first();

        return $data2->BillAmt_value;
       
    }

    private function missing_dd_unknown($previousDate)
    {
        $data2 = Advice::select(DB::raw('SUM(actual_amount) as actual_amount'))->where("file_date","=",$previousDate)->where("code","44")->whereNull("related_table_id")->first();

        if(!empty($data2->actual_amount))
        {
            return -($data2->actual_amount);
        }
        else
        {
            return 0;
        }
    }

    private function mainRecoUnauthorized_dd_current($reportDate)
    {
        $data = Directdebits::select(DB::raw('SUM(amount) as amount'))->where("Due_Date",$reportDate)->where("status","Processed")->first();

        return $data->amount;

    }

    private function dec_dd_not_cr_uncr($reportDate)
    {
        $data = Directdebits::select(DB::raw('SUM(amount) as amount'))->where("Due_Date",$reportDate)->where("status","Rejected")->first();

        return $data->amount;

    }

    private function mainRecoBalance_adj_credits($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","credit")->where("Desc","NOT LIKE","%chargeback%")->first();

        return $data->Amount_value;
    }


    private function mainRecoBalance_adj_debits($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","debit")->where("Desc","NOT LIKE","%chargeback%")->where("Desc","NOT LIKE","%Debit General%")->first();

        return $data->Amount_value;
    }

    private function mainRecoCharge_back_credits($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","credit")->where("Desc","LIKE","%chargeback%")->first();

        return $data->Amount_value;
    }

    private function mainRecoCharge_back_debits($startDate , $endDate)
    {
        $data = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value'))->whereBetween("SettlementDate",[$startDate , $endDate])->where("Amount_direction","debit")->where("Desc","LIKE","%chargeback%")->first();

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

    private function mainRecopos_pos_interchange_value($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))
                ->whereIn("Desc",["Interchange Fee"])
                ->whereIn("TxnCode_ProcCode",["200000","170000"])
                ->where("Amt_direction","debit")
                ->whereBetween("SettlementDate",[$startDate , $endDate])
                ->first();

        $data1 = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))
                ->whereIn("Desc",["Interchange Fee"])
                ->where("Amt_direction","credit")
                ->whereBetween("SettlementDate",[$startDate , $endDate])
                ->first();

        $dataVal1 = Cardchrgbackrepres::select(DB::raw('SUM(Fee_value) as Fee_value'))
                    ->whereBetween("SettlementAmt_date",[$startDate,$endDate])
                    ->where("TxnCode_direction","Credit")
                    ->first();

        $dataVal2 = Cardchrgbackrepres::select(DB::raw('SUM(Fee_value) as Fee_value'))
                    ->whereBetween("SettlementAmt_date",[$startDate,$endDate])
                    ->where("TxnCode_direction","Debit")
                    ->first();

        $posIntVal = 0;

        $posIntVal = $data->Amt_value - $data1->Amt_value + $dataVal1->Fee_value + $dataVal2->Fee_value;

        return ($posIntVal * -1);
    }

    private function mainRecopos_interchang_pos_dr($startDate , $endDate)
    {
        $dataVal1 = Cardfinancial::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type","pos")->where("TxnCode_direction","debit")->first();

        return $dataVal1->Fee_value;
    }

    private function mainRecopos_interchang_pos_cb($startDate , $endDate)
    {
        $dataVal1 = Cardfinancial::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type","pos_cb")->where("TxnCode_direction","debit")->first();

        $dataVal2 = Cardfinancial::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type","pos_cb")->where("TxnCode_direction","credit")->first();

        return ($dataVal1->Fee_value - $dataVal2->Fee_value);
    }

    private function pos_interchang_pos_chargeback($startDate , $endDate)
    {
        $dataVal1 = Cardchrgbackrepres::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementAmt_date",[$startDate,$endDate])->where("TxnCode_direction","credit")->first();

        return $dataVal1->Fee_value;
    }

    private function pos_interchang_pos_repres($startDate , $endDate)
    {
        $dataVal1 = Cardchrgbackrepres::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementAmt_date",[$startDate,$endDate])->where("TxnCode_direction","Debit")->where("RecordType","REPRES")->first();

        return $dataVal1->Fee_value;
    }

    private function mainRecopos_interchang_pos_re($startDate , $endDate)
    {
        $dataVal1 = Cardfinancial::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type","pos_re")->first();

        return $dataVal1->Fee_value;
    }

    private function mainRecopos_interchang_pos_cr($startDate , $endDate)
    {
        $dataVal2 = Cardfinancial::select(DB::raw('SUM(Fee_value) as Fee_value'))->whereBetween("SettlementDate",[$startDate,$endDate])->where("TxnCode_Type","pos")->where("TxnCode_direction","credit")->first();

        return $dataVal2->Fee_value;
    }

    private function mainRecopos_atm_interchang_current($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))
                ->whereIn("Desc",["Interchange Fee"])
                ->whereIn("TxnCode_ProcCode",["010000","012000"])
                ->where("Amt_direction","debit")
                ->whereBetween("SettlementDate",[$startDate , $endDate])
                ->first();

        if(!empty($data->Amt_value))
        {
            return -$data->Amt_value;
        }
        else
        {
            return 0;
        }
    }


    private function mainRecoFpOutFee($startDate , $endDate)
    {
        // $data = Agencybanking::select(DB::raw('SUM(Fee_value) as Fee_value'))->where("banking_type","Approved")->where("CashType","PAY")->whereBetween("SettlementDate",[$startDate , $endDate])->first();

        $data = Agencybankingfee::select(DB::raw('SUM(Amt_value) as Amt_value'))
                ->whereBetween("SettlementDate",[$startDate , $endDate])
                ->first();

        return $data->Amt_value;
    }

    private function mainRecoAtmFee($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))
                ->whereIn("Desc",["Domestic Fee","NonDomestic Fee"])
                ->where("Amt_direction","debit")
                ->whereBetween("SettlementDate",[$startDate , $endDate])
                ->first();

        return $data->Amt_value;
    }

    private function mainRecoforex_fee($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate , $endDate])
            ->where("Desc","Forex Fee")->first();

        return $data->Amt_value;
    }

    private function mainRecocode_999999_fee($startDate , $endDate)
    {
        $data = Cardfee::select(DB::raw('SUM(Amt_value) as Amt_value'))->whereBetween("SettlementDate",[$startDate , $endDate])
            ->whereIn("TxnCode_ProcCode",["999999","084999"])->first();

        return $data->Amt_value;
    }

    private function mainRecoTxnSattledNotAdjCurr($reportDate)
    {
        $data2 = Dailybalanceshift::select(DB::raw('SUM(trans_settled_not_adj_gps_2) as trans_settled_not_adj_gps_2'))->where("repot_date",$reportDate)->first();

        return $data2->trans_settled_not_adj_gps_2;
    }

    private function txn_sattled_not_adj_prev($reportDate)
    {
        $data1 = Dailybalanceshift::select(DB::raw('SUM(trans_settled_not_adj_gps) as trans_settled_not_adj_gps'))->where("repot_date",$reportDate)->first();

        return $data1->trans_settled_not_adj_gps;
    }

    private function bankStatementFlgCalculation($reportDate , $flg)
    {
        $data = Bankstatement::select(DB::raw('SUM(credit) as credit_value') , DB::raw('SUM(debit) as debit_value'))->where("date",$reportDate)->where("extra_flags",$flg)->first();

        $resultValue = $data->credit_value - $data->debit_value;
        return $resultValue;
    }

    private function balanceAdjustmentFlgCalculation($startDate , $endDate , $flg)
    {
        $data1 = Cardbaladjust::whereBetween("SettlementDate",[$startDate , $endDate])->where("extra_flags",$flg)->get();
        if(count($data1) != 0)
        {
            $totalAmt = 0;

            foreach ($data1 as $key => $value) 
            {
                if(!empty($value->extra_flags_cr_dr))
                {
                    if($value->extra_flags_cr_dr == "debit")
                        $totalAmt = $totalAmt - $value->Amount_value;
                    else
                        $totalAmt = $totalAmt + $value->Amount_value;
                }
                else
                {
                    if($value->Amount_direction == "debit")
                        $totalAmt = $totalAmt - $value->Amount_value;
                    else
                        $totalAmt = $totalAmt + $value->Amount_value;
                }
            }
            return $totalAmt;
        }
        return 0;
    }

    private function balanceAdjustmentFlgCalculationfpinreturn($startDate , $endDate , $flg)
    {
        $data1 = Cardbaladjust::select(DB::raw('SUM(Amount_value) as Amount_value') , 'Amount_direction')->whereBetween("SettlementDate",[$startDate , $endDate])->where("extra_flags",$flg)->first();
        
        if(!empty($data1))
        {
            return -$data1->Amount_value;
        }
        return 0;
    }
}
