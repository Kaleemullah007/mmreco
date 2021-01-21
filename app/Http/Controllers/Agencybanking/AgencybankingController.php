<?php
namespace App\Http\Controllers\Agencybanking;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use DB;
use Input;
use Redirect;
use View;
use Carbon\Carbon;
use Validator;
use File;
use App\Models\Agencybanking;
use App\Models\Agencybankingfee;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class AgencybankingController extends Controller
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
                // return Redirect::back()->withErrors($validator)->withInput();
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

        return View::make('agencybanking/approved')->with('filterColumn', $filterColumn);
    }

    public function getDatatable()
    {        
        $params = array();
        $params['banking_type'] = 'Approved';
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Agencybanking::getDatatableData($params);       
        $rows = array();

        foreach ($result['data'] as $agencybanking) {

            $recoFlgHtml='';
            if($agencybanking->reco_flg == 'Y')
            {
                if($agencybanking->reco_date >  $agencybanking->SettlementDate)
                {
                    $recoFlgHtml = '<span class="recoYesYellow">'.e($agencybanking->CashType).'</span>';
                }
                else
                {
                    $recoFlgHtml = '<span class="recoYes">'.e($agencybanking->CashType).'</span>';
                }
            }
            else
            {
                $recoFlgHtml = '<span class="">'.e($agencybanking->CashType).'</span>';
            }

            $fpoutRecoDate = "";
            $OutwardAcceptedValue = "";
            if(!empty($agencybanking->fpoutrel[0]))
            {
                $fpoutRecoDate = $agencybanking->fpoutrel[0]->reco_date;
                $OutwardAcceptedValue = $agencybanking->fpoutrel[0]->OutwardAcceptedValue + $agencybanking->fpoutrel[0]->OutwardRejectedValue;
            }

            $rows[] = array(
                'CashType'    => $recoFlgHtml,                
                'reco_date'    => e($agencybanking->reco_date),               
                'BankingId'   => e($agencybanking->BankingId),
                'SettlementDate'  => e($agencybanking->SettlementDate),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->Desc.'">'. $agencybanking->Desc .'</span>',
                'Card_PAN'  => e($agencybanking->Card_PAN),                
                'AgencyAccount_no'    => e($agencybanking->AgencyAccount_no),                
                'AgencyAccount_sortcode'  => e($agencybanking->AgencyAccount_sortcode),                                           
                'External_sortcode'  => e($agencybanking->External_sortcode),                                           
                'External_bankacc'  => e($agencybanking->External_bankacc),                                           
                'External_name'  => e($agencybanking->External_name),                                           
                'CashAmt_value'  => round(e($agencybanking->CashAmt_value),2),                                           
                'Fee_direction'  => e($agencybanking->Fee_direction),  
                'Card_productid'  => e($agencybanking->Card_productid),                                           
                'Card_product'  => e($agencybanking->Card_product),                                           
                'Card_programid'  => e($agencybanking->Card_programid),                                           
                'Card_branchcode'  => e($agencybanking->Card_branchcode),                                           
                'AgencyAccount_type'  => e($agencybanking->AgencyAccount_type),                                           
                'AgencyAccount_bankacc'  => e($agencybanking->AgencyAccount_bankacc),                                           
                'AgencyAccount_name'  => e($agencybanking->AgencyAccount_name),                                           
                'CashCode_direction'  => e($agencybanking->CashCode_direction),                                           
                'CashCode_CashType'  => e($agencybanking->CashCode_CashType),                                           
                'CashCode_CashGroup'  => e($agencybanking->CashCode_CashGroup),                                           
                'CashAmt_currency'  => e($agencybanking->CashAmt_currency),                                           
                'Fee_value'  => e($agencybanking->Fee_value),                                           
                'Fee_currency'  => e($agencybanking->Fee_currency),                                           
                'BillAmt_value'  => e($agencybanking->BillAmt_value),                                           
                'BillAmt_currency'  => e($agencybanking->BillAmt_currency),                                           
                'BillAmt_rate'  => e($agencybanking->BillAmt_rate),                                           
                'OrigTxnAmt_value'  => e($agencybanking->OrigTxnAmt_value),                                           
                'OrigTxnAmt_currency'  => e($agencybanking->OrigTxnAmt_currency),                                           
                'OrigTxnAmt_partial'  => e($agencybanking->OrigTxnAmt_partial),                                           
                'OrigTxnAmt_origItemId'  => e($agencybanking->OrigTxnAmt_origItemId),                                           
                'reco_flg'  => e($agencybanking->reco_flg),          

                'File_filename'  => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->File_filename.'">'. $agencybanking->File_filename .'</span>',                                           
                'File_filedate'  => (!empty($agencybanking->File_filedate))? date("Y-m-d H:i:s",strtotime(e($agencybanking->File_filedate))) : "-",    

                'file_date'  => e($agencybanking->file_date),
                "file_name" => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->file_name.'">'. $agencybanking->file_name .'</span>',
                "fpoutRecoDate" => $fpoutRecoDate,                                
                "OutwardAcceptedValue" => $OutwardAcceptedValue,                                
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
    public function getDeclinedIndex()
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
             

        return View::make('agencybanking/declined')->with('filterColumn', $filterColumn);
    }

    public function getDeclinedDatatable()
    {
        $params = array();
        $params['banking_type'] = 'Declined';
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Agencybanking::getDatatableData($params);
        $rows = array();
        foreach ($result['data'] as $agencybanking) {

            $recoFlgHtml = '';
            if($agencybanking->reco_flg == 'Y')
            {
                $recoFlgHtml = '<span class="recoYes">'.e($agencybanking->CashType).'</span>';
            }
            else
            {
                $recoFlgHtml = '<span class="">'.e($agencybanking->CashType).'</span>';
            }
            $fpoutRecoDate = "";
            $OutwardAcceptedValue = "";
            if(!empty($agencybanking->fpoutrel[0]))
            {
                $fpoutRecoDate = $agencybanking->fpoutrel[0]->reco_date;
                $OutwardAcceptedValue = $agencybanking->fpoutrel[0]->OutwardAcceptedValue + $agencybanking->fpoutrel[0]->OutwardRejectedValue;
            }

            $rows[] = array(
                'CashType'    => $recoFlgHtml,  
                'reco_date'    => e($agencybanking->reco_date),              
                'BankingId'   => e($agencybanking->BankingId),
                'SettlementDate'  => e($agencybanking->SettlementDate),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->Desc.'">'. $agencybanking->Desc .'</span>',
                'DeclineReason'   => e($agencybanking->DeclineReason),
                'Card_PAN'  => e($agencybanking->Card_PAN),                
                'AgencyAccount_no'    => e($agencybanking->AgencyAccount_no),                
                'AgencyAccount_sortcode'  => e($agencybanking->AgencyAccount_sortcode),                                           
                'External_sortcode'  => e($agencybanking->External_sortcode),                                           
                'External_bankacc'  => e($agencybanking->External_bankacc),                                           
                'External_name'  => e($agencybanking->External_name),                                           
                'CashAmt_value'  => round(e($agencybanking->CashAmt_value),2),                                           
                'Fee_direction'  => e($agencybanking->Fee_direction), 

                'Card_productid'  => e($agencybanking->Card_productid),                                           
                'Card_product'  => e($agencybanking->Card_product),                                           
                'Card_programid'  => e($agencybanking->Card_programid),                                           
                'Card_branchcode'  => e($agencybanking->Card_branchcode),                                           
                'AgencyAccount_type'  => e($agencybanking->AgencyAccount_type),                                           
                'AgencyAccount_bankacc'  => e($agencybanking->AgencyAccount_bankacc),                                           
                'AgencyAccount_name'  => e($agencybanking->AgencyAccount_name),                                           
                'CashCode_direction'  => e($agencybanking->CashCode_direction),                                           
                'CashCode_CashType'  => e($agencybanking->CashCode_CashType),                                           
                'CashCode_CashGroup'  => e($agencybanking->CashCode_CashGroup),                                           
                'CashAmt_currency'  => e($agencybanking->CashAmt_currency),                                           
                'Fee_value'  => e($agencybanking->Fee_value),                                           
                'Fee_currency'  => e($agencybanking->Fee_currency),                                           
                'BillAmt_value'  => e($agencybanking->BillAmt_value),                                           
                'BillAmt_currency'  => e($agencybanking->BillAmt_currency),                                           
                'BillAmt_rate'  => e($agencybanking->BillAmt_rate),                                           
                'OrigTxnAmt_value'  => e($agencybanking->OrigTxnAmt_value),                                           
                'OrigTxnAmt_currency'  => e($agencybanking->OrigTxnAmt_currency),                                           
                'OrigTxnAmt_partial'  => e($agencybanking->OrigTxnAmt_partial),                                           
                'OrigTxnAmt_origItemId'  => e($agencybanking->OrigTxnAmt_origItemId),
                'DeclineReason'  => e($agencybanking->DeclineReason),

                'reco_flg'  => e($agencybanking->reco_flg), 
                
                'File_filename'  => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->File_filename.'">'. $agencybanking->File_filename .'</span>',                                           
                'File_filedate'  => (!empty($agencybanking->File_filedate))? date("Y-m-d H:i:s",strtotime(e($agencybanking->File_filedate))) : "-",    
                
                'file_date'  => e($agencybanking->file_date),
                "file_name" => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->file_name.'">'. $agencybanking->file_name .'</span>',                   
                                                          
                'fpoutRecoDate'  => $fpoutRecoDate,
                'OutwardAcceptedValue'  => $OutwardAcceptedValue,
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

        return View::make('agencybanking/fee')->with('filterColumn', $filterColumn);
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
        $result = Agencybankingfee::getDatatableData($params);
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $rows[] = array(
                'BankingFeeId'    => e($data->BankingFeeId),                
                'AbId'   => e($data->AbId),
                'SettlementDate'  => e($data->SettlementDate),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->Desc.'">'. $data->Desc .'</span>',
                'Card_PAN'   => e($data->Card_PAN),
                'AgencyAccount_no'  => e($data->AgencyAccount_no),                
                'AgencyAccount_sortcode'    => e($data->AgencyAccount_sortcode),                
                'AgencyAccount_bankacc'  => e($data->AgencyAccount_bankacc),                                           
                'AgencyAccount_name'  => e($data->AgencyAccount_name),                                           
                'Amt_direction'  => e($data->Amt_direction),                                           
                'Amt_value'  => round(e($data->Amt_value),2),                                           
                'created_at'  => e($data->created_at),                   
                'file_date'  => e($data->file_date),
                "file_name" => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->file_name.'">'. $data->file_name .'</span>',                                                             
            );
        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }
}
