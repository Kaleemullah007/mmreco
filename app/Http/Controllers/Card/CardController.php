<?php
namespace App\Http\Controllers\Card;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use DB;
use Input;
use Redirect;
use View;
use Carbon\Carbon;
use Validator;

use App\Models\Cardauthorisation;
use App\Models\Cardbaladjust;
use App\Models\Cardfee;
use App\Models\Cardfinancial;
use App\Models\Cardloadunload;
use App\Models\Cardchrgbackrepres;
use App\Models\Cardevent;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class CardController extends Controller
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
    public function getAuthorisationIndex()
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
    

        return View::make('card/authorisation')->with('filterColumn', $filterColumn);
    }

    public function getAuthorisationDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardauthorisation::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'RecType'    => e($data->RecType),                
                'AuthId'   => e($data->AuthId),
                'SettlementDate'  => e($data->SettlementDate),                
                'Card_PAN'  => e($data->Card_PAN),                
                'Account_no'    => e($data->Account_no),                
                'TxnCode_direction'  => e($data->TxnCode_direction),                                           
                'TxnCode_Type'  => e($data->TxnCode_Type),                                           
                'BillAmt_value'  => round(e($data->BillAmt_value),2),             
                'BillAmt_rate'  => round(e($data->BillAmt_rate),2),      
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),
                "file_name" => e($data->file_name),
                "LocalDate" => e($data->LocalDate),
                "ApprCode" => e($data->ApprCode),
                "MerchCode" => e($data->MerchCode),
                "Schema" => e($data->Schema),
                "ReversalReason" => e($data->ReversalReason),
                "Card_product" => e($data->Card_product),
                "Card_programId" => e($data->Card_programId),
                "Card_branchCode" => e($data->Card_branchCode),
                "Card_productid" => e($data->Card_productid),
                "Account_type" => e($data->Account_type),
                "TxnCode_Group" => e($data->TxnCode_Group),
                "TxnCode_ProcCode" => e($data->TxnCode_ProcCode),
                "TxnAmt_value" => e($data->TxnAmt_value),
                "TxnAmt_currency" => e($data->TxnAmt_currency),
                "CashbackAmt_value" => e($data->CashbackAmt_value),
                "CashbackAmt_currency" => e($data->CashbackAmt_currency),
                "BillAmt_currency" => e($data->BillAmt_currency),
                "BillAmt_clientfxrate" => e($data->BillAmt_clientfxrate),
                "Trace_auditno" => e($data->Trace_auditno),
                "Trace_origauditno" => e($data->Trace_origauditno),
                "Trace_Retrefno" => e($data->Trace_Retrefno),
                "Term_code" => e($data->Term_code),
                "Term_location" => e($data->Term_location),
                "Term_street" => e($data->Term_street),
                "Term_city" => e($data->Term_city),
                "Term_country" => e($data->Term_country),
                "Term_inputcapability" => e($data->Term_inputcapability),
                "Term_authcapability" => e($data->Term_authcapability),
                "Txn_cardholderpresent" => e($data->Txn_cardholderpresent),
                "Txn_cardpresent" => e($data->Txn_cardpresent),
                "Txn_cardinputmethod" => e($data->Txn_cardinputmethod),
                "Txn_cardauthmethod" => e($data->Txn_cardauthmethod),
                "Txn_cardauthentity" => e($data->Txn_cardauthentity),
                "Txn_TVR" => e($data->Txn_TVR),
                "MsgSource_value" => e($data->MsgSource_value),
                "MsgSource_domesticMaestro" => e($data->MsgSource_domesticMaestro),
                "PaddingAmt_value" => e($data->PaddingAmt_value),
                "PaddingAmt_currency" => e($data->PaddingAmt_currency),
                "CommissionAmt_value" => e($data->CommissionAmt_value),
                "CommissionAmt_currency" => e($data->CommissionAmt_currency),
                "Classification_RCC" => e($data->Classification_RCC),
                "Classification_MCC" => e($data->Classification_MCC),
                "Response_approved" => e($data->Response_approved),
                "Response_actioncode" => e($data->Response_actioncode),
                "Response_responsecode" => e($data->Response_responsecode),
                "OrigTxnAmt_value" => e($data->OrigTxnAmt_value),
                "OrigTxnAmt_currency" => e($data->OrigTxnAmt_currency),
                "OrigTxnAmt_origItemId" => e($data->OrigTxnAmt_origItemId),
                "OrigTxnAmt_partial" => e($data->OrigTxnAmt_partial),
                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getBalAdjustIndex()
    {
        $balAdjExtraFlg = Helper::balanceAdjustmentFlg();

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

        $flagDRCR = array("credit"=>"credit","debit"=>"debit");

        return View::make('card/baladjust')->with('filterColumn', $filterColumn)->with('balAdjExtraFlg',$balAdjExtraFlg)->with("flagDRCR",$flagDRCR);
    }

    public function getBalAdjustDatatable()
    {        
        $balAdjExtraFlg = Helper::balanceAdjustmentFlg();

        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardbaladjust::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {
            
            $extra_flagsEdit = '';
            if(!empty($data->extra_flags))
            {
                $extraFlg = $balAdjExtraFlg[$data->extra_flags];

                $extra_flagsEdit = '<span  data-toggle="tooltip" data-original-title="'.e($extraFlg).'" data-placement="left"> <a class="extra_flagsEdit" data-name="extra_flags" data-type = "select" data-url="'.route('setExtraFlg').'" data-value="'.e($data->extra_flags).'" data-title="Set Flag" data-pk="'.$data->ids.'" style="display:block; white-space:nowrap; word-break:break-word;"> ' .e($extraFlg).'</a> <span>';
            }
            else
            {
                $extra_flagsEdit = '<span  data-toggle="tooltip" data-original-title="'.e($data->extra_flags).'" data-placement="left"> <a class="extra_flagsEdit" data-name="extra_flags" data-type = "select" data-url="'.route('setExtraFlg').'" data-title="Set Flag" data-value="'.e($data->extra_flags).'" data-pk="'.$data->ids.'" style="display:block; white-space:nowrap; word-break:break-word;">-</a> <span>';
            }

            $extra_flags_cr_drEdit = '';
            

            $extra_flags_cr_drEdit = '<a class="extra_flags_cr_drEdit" data-name="extra_flags_cr_dr" data-type = "select" data-url="'.route('setExtraFlgDrCr').'" data-value="'.e($data->extra_flags_cr_dr).'" data-title="Set Flag CR/DR" data-pk="'.$data->ids.'" style="display:block; white-space:nowrap; word-break:break-word;"> ' .e($data->extra_flags_cr_dr).'</a>';

            $chkBtn = "";
            
            $chkBtn = '<input type="checkbox" name="selectchk" id="selectchk-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" >';

            $rows[] = array(
                'chkBtn' => $chkBtn,
                'extra_flags' => $extra_flagsEdit,
                'extra_flags_cr_drEdit' => $extra_flags_cr_drEdit,
                'RecType'    => e($data->RecType),                
                'AdjustId'   => e($data->AdjustId),
                'SettlementDate'  => e($data->SettlementDate),                
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->Desc.'">'. $data->Desc .'</span>',          
                'Card_PAN'  => e($data->Card_PAN),                
                'Account_no'    => e($data->Account_no),                
                'Amount_direction'  => e($data->Amount_direction),                                           
                'Amount_value'  => round(e($data->Amount_value),2),                                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),   

                "MessageId"  => e($data->MessageId),
                "LocalDate"  => e($data->LocalDate),
                "VoidedAdjustId"  => e($data->VoidedAdjustId),
                "MerchCode"  => e($data->MerchCode),
                "Card_product"  => e($data->Card_product),
                "Card_programid"  => e($data->Card_programid),
                "Card_branchcode"  => e($data->Card_branchcode),
                "Card_productid"  => e($data->Card_productid),
                "Account_type"  => e($data->Account_type),
                "Amount_currency"  => e($data->Amount_currency),
                "reco_date"  => e($data->reco_date),
                "file_name"  => e($data->file_name),
                                        
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function setExtraFlg(Request $request)
    {
        if(!empty($request->get('value')) && !empty($request->get('pk')) )
        {
            Cardbaladjust::where("id",$request->get('pk'))->update(["extra_flags" => $request->get('value')]);
        }
        else if(!empty($request->get('pk')) && empty($request->get('value')))
        {
            Cardbaladjust::where("id",$request->get('pk'))->update(["extra_flags" => NULL]);   
        }
        return json_encode(array("success" => "success"));
    }

    public function setExtraFlgDrCr(Request $request)
    {
        if(!empty($request->get('value')) && !empty($request->get('pk')) )
        {
            Cardbaladjust::where("id",$request->get('pk'))->update(["extra_flags_cr_dr" => $request->get('value')]);
        }
        return json_encode(array("success" => "success"));
    }

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getFeeIndex()
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
       

        return View::make('card/fee')->with('filterColumn', $filterColumn);
    }

    public function getFeeDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardfee::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
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

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getFinancialIndex()
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
     

        return View::make('card/financial')->with('filterColumn', $filterColumn);
    }

    public function getFinancialDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardfinancial::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'RecordType'    => e($data->RecordType),                
                'FinId'   => e($data->FinId),
                'AuthId'  => e($data->AuthId),                
                'LocalDate'  => date('Y-m-d',strtotime(e($data->LocalDate))),
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

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getLoadunloadIndex()
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

        return View::make('card/loadunload')->with('filterColumn', $filterColumn);
    }

    public function getLoadunloadDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardloadunload::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'RecordType'    => e($data->RecordType),                
                'LoadUnloadId'   => e($data->LoadUnloadId),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->Desc.'">'. $data->Desc .'</span>',   
                'SettlementDate'  => e($data->SettlementDate),                
                'Card_PAN'    => e($data->Card_PAN),                
                'Account_no'  => e($data->Account_no),                                           
                'Amount_direction'  => e($data->Amount_direction),                                           
                'Amount_value'  => round(e($data->Amount_value),2),                                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),  
                                                         
                'file_name'  => e($data->file_name),                                           
                'LocalDate'  => e($data->LocalDate),                                           
                'MessageId'  => e($data->MessageId),                                           
                'MerchCode'  => e($data->MerchCode),                                           
                'LoadSource'  => e($data->LoadSource),                                           
                'LoadType'  => e($data->LoadType),                                           
                'VoidedLoadUnloadId'  => e($data->VoidedLoadUnloadId),                                           
                'Card_productid'  => e($data->Card_productid),                                           
                'Card_product'  => e($data->Card_product),                                           
                'Card_programid'  => e($data->Card_programid),                                           
                'Card_branchcode'  => e($data->Card_branchcode),                                           
                'Account_type'  => e($data->Account_type),                                           
                'Amount_currency'  => e($data->Amount_currency),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getChrgbackrepresIndex()
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


        return View::make('card/chrgbackrepres')->with('filterColumn', $filterColumn);
    }

    public function getChrgbackrepresDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardchrgbackrepres::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'RecordType'    => e($data->RecordType),                
                'ChgbackRepresId'   => e($data->ChgbackRepresId),
                'LocalDate'   => date('Y-m-d',strtotime(e($data->LocalDate))),
                'SettlementDate'  => e($data->SettlementDate),                
                'Card_PAN'    => e($data->Card_PAN),                
                'Account_no'  => e($data->Account_no),                                           
                'TxnCode_direction'  => e($data->TxnCode_direction),                                           
                'TxnCode_Type'  => e($data->TxnCode_Type),                                           
                'TxnAmt_value'  => round(e($data->TxnAmt_value),2),
                'CashbackAmt_value'  => round(e($data->CashbackAmt_value),2),
                'BillAmt_value'  => round(e($data->BillAmt_value),2),
                'Fee_direction'  => e($data->Fee_direction),                      
                'Fee_value'  => round(e($data->Fee_value),2),
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),         

                "file_name"=> e($data->file_name),
                "ApprCode"=> e($data->ApprCode),
                "MerchCode"=> e($data->MerchCode),
                "Schema"=> e($data->Schema),
                "Repeat"=> e($data->Repeat),
                "ARN"=> e($data->ARN),
                "FIID"=> e($data->FIID),
                "RIID"=> e($data->RIID),
                "ReasonCode"=> e($data->ReasonCode),
                "PartialReversal"=> e($data->PartialReversal),
                "Card_product"=> e($data->Card_product),
                "Card_programid"=> e($data->Card_programid),
                "Card_branchcode"=> e($data->Card_branchcode),
                "Card_productid"=> e($data->Card_productid),
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
                "SettlementAmt_value"=> e($data->SettlementAmt_value),
                "SettlementAmt_currency"=> e($data->SettlementAmt_currency),
                "SettlementAmt_rate"=> e($data->SettlementAmt_rate),
                "SettlementAmt_date"=> e($data->SettlementAmt_date),
                "Fee_currency"=> e($data->Fee_currency),
                "Classification_RCC"=> e($data->Classification_RCC),
                "Classification_MCC"=> e($data->Classification_MCC),
                "OrigTxnAmt_value"=> e($data->OrigTxnAmt_value),
                "OrigTxnAmt_currency"=> e($data->OrigTxnAmt_currency),
                "OrigTxnAmt_origItemId"=> e($data->OrigTxnAmt_origItemId),
                "OrigTxnAmt_partial"=> e($data->OrigTxnAmt_partial),
                                  
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    /**
    * Returns a view that invokes the ajax tables which actually contains
    * the content for the users listing, which is generated in getDatatable().
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @see BankbalanceController::getDatatable() method that generates the JSON response
    * @since [v1.0]
    * @return View
    */
    public function getEventIndex()
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

        return View::make('card/event')->with('filterColumn', $filterColumn);
    }

    public function getEventDatatable()
    {        
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Cardevent::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'Card_PAN'    => e($data->Card_PAN),                
                'Event_Type'   => e($data->Event_Type),
                'Event_ActivationDate'   => date('Y-m-d',strtotime(e($data->Event_ActivationDate))),
                'Event_StatCode'  => e($data->Event_StatCode),                                
                'Event_Date'  => date('Y-m-d',strtotime(e($data->Event_Date))),                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),         

                'file_name'  => e($data->file_name),                                           
                'Card_productid'  => e($data->Card_productid),                                           
                'Event_Source'  => e($data->Event_Source),                                           
                'Event_OldStatCode'  => e($data->Event_OldStatCode),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function setmultiadjflag(Request $request)
    {
        if(!empty($request->get('adjIds')) && !empty($request->get('extra_flg')) && !empty($request->get('extra_flags_cr_dr')))
        {
            Cardbaladjust::whereIn("id",$request->get('adjIds'))->update(["extra_flags"=>$request->get('extra_flg') , "extra_flags_cr_dr" => $request->get('extra_flags_cr_dr')]);
        }
        echo "success";
        exit;
    }
}
