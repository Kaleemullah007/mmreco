<?php
namespace App\Http\Controllers\Manualtransaction;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Actionlog;
use App\Models\Agencybanking;
use App\Models\Cardauthorisation;
use App\Models\Cardbaladjust;
use App\Models\Cardchrgbackrepres;
use App\Models\Cardfinancial;
use App\Models\Cardfee;
use App\Models\Cardloadunload;
use App\Models\Mastercardfee;
use App\Models\Agencybankingfee;
use App\Models\Cardevent;
use App\Models\Txnfilesupload;
use App\Models\Bankstatement;
use App\Models\Bankmaster;
use App\Models\Fpout;
use App\Models\Txnmappingint;
use App\Models\Directdebits;
use App\Models\Manualselecteddata;
use App\Http\Requests\ImportAgencybankingRequest;
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
use Validator;
use Session;

/**
 * This controller handles all actions related to Users for
 * the Parextech Asset Management application.
 *
 * @version    v1.0
 */


class ManualtransactionController extends Controller
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


    public function comparetxn(Request $request)
    { 
       // session(['bstFilterData'=>array()]);
        $fData = array(); 
        $fData = $request->session()->get('txnfilter');
        $txnDrop = '';
        if(!empty($fData['TransactionDrop']))
            $txnDrop = $fData['TransactionDrop'];
        $txn_start_date = '';
        if(!empty($fData['txn_start_date']))
            $txn_start_date = $fData['txn_start_date'];
        $txn_end_date = '';
        if(!empty($fData['txn_end_date']))
            $txn_end_date = $fData['txn_end_date'];

        $Bankmaster = Bankmaster::where('status','=','Active')->pluck('name','id');
        $bankStatementData = array();
        $bankStatementData = Bankstatement::select("bank_statement.*","bank_statement.id as ids")->join("manual_selected_data","manual_selected_data.related_table_id","=","bank_statement.id")->where("table_type","bank_statement")->where("manual_selected_data.user_id",Auth::user()->id)->first();

        $agencybankingApprovedData = array();
        $agencybankingApprovedData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")->join("manual_selected_data","manual_selected_data.related_table_id","=","agencybanking.id")->where("table_type","agencybanking_approved")->where("manual_selected_data.user_id",Auth::user()->id)->get();

        $agencybankingDeclinedData = array();
        $agencybankingDeclinedData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")->join("manual_selected_data","manual_selected_data.related_table_id","=","agencybanking.id")->where("table_type","agencybanking_declined")->where("manual_selected_data.user_id",Auth::user()->id)->get();

        $fp_outData = array();
        $fp_outData = Fpout::select("fp_out.*","fp_out.id as ids")->join("manual_selected_data","manual_selected_data.related_table_id","=","fp_out.id")->where("table_type","fp_out")->where("manual_selected_data.user_id",Auth::user()->id)->get();
  
        $balance_adjData = array();
        $balance_adjData = Cardbaladjust::select("cardbaladjust.*","cardbaladjust.id as ids")->join("manual_selected_data","manual_selected_data.related_table_id","=","cardbaladjust.id")->where("table_type","balance_adj")->where("manual_selected_data.user_id",Auth::user()->id)->get();
  
        $filterColumn = array();
            
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));    

        $abApprovedfilterColumn = array();
                
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));

        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));

        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abApprovedfilterColumn[]=array("filter" => array("type" => "input"));

        $abDeclinedfilterColumn = array();
                
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));

        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input"));        
        $abDeclinedfilterColumn[]=array("filter" => array("type" => "input")); 

        $fp_outfilterColumn  = array();
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));        
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));        
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));        
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));        
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));
        $fp_outfilterColumn[]=array("filter" => array("type" => "input"));

        $balance_adjfilterColumn  = array();
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));        
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));        
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));        
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));        
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));
        $balance_adjfilterColumn[]=array("filter" => array("type" => "input"));

        $extra_Flg = Helper::bankStatementExtraFlg();

        $TransactionDrop = array("agencybanking_approved"=>"Approved Agency Banking" , "agencybanking_declined" => "Declined Agency Banking" , "balance_adj" => "Balance Adjustement" , "fp_out" => "Faster Payment Out");
        return View::make('manualtransaction/comparetxn',compact('Bankmaster'))->with('filterColumn', $filterColumn)->with("bankStatementData",$bankStatementData)->with("TransactionDrop",$TransactionDrop)->with('abApprovedfilterColumn',$abApprovedfilterColumn)->with('abDeclinedfilterColumn',$abDeclinedfilterColumn)->with('fp_outfilterColumn',$fp_outfilterColumn)->with('agencybankingApprovedData',$agencybankingApprovedData)->with('agencybankingDeclinedData',$agencybankingDeclinedData)->with('fp_outData',$fp_outData)->with('extra_Flg',$extra_Flg)->with('balance_adjfilterColumn',$balance_adjfilterColumn)->with('balance_adjData',$balance_adjData)->with('fData',$fData)->with('txnDrop',$txnDrop)->with('txn_start_date',$txn_start_date)->with('txn_end_date',$txn_end_date);
    }

    public function getBstDatatable(Request $request)
    {
        $params = array();
        $filterData = $request->get('filterData');

        $selectedBstVal = Manualselecteddata::where("table_type" , 'bank_statement')->where("manual_selected_data.user_id",Auth::user()->id)->pluck("related_table_id","id")->toArray();

        if(!empty($filterData))
        {
            $filterData = json_decode($filterData,1);
            session(['bstFilterData'=>$filterData]);
        }

        $fData = $request->session()->get('bstFilterData');

        if(!empty($fData))
        {
            $params['bank_master_id'] = $fData['Bankmaster'];
            $params['start_date'] = date("Y-m-d",strtotime($fData['bst_start_date']));
            $params['end_date'] = date("Y-m-d",strtotime($fData['bst_end_date']));
        }

        $result = Bankstatement::getAutoCompareDatatableData($params);               
        
        $rows = array();
        foreach ($result['data'] as $data) {

            if(in_array($data->ids, $selectedBstVal))
            {
                $actions = '<input type="radio" name="bstRadio" id="bstRadio-'.$data->ids.'" class="selectchk radioSelected" value="'.$data->ids.'" onchange="bstRadioSelect(this, \''.$data->ids.'\' , \'bank_statement\')" checked="true">';
            }
            else
            {
                $actions = '<input type="radio" name="bstRadio" id="bstRadio-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" onchange="bstRadioSelect(this, \''.$data->ids.'\' , \'bank_statement\')">';
            }
            
            
            $nestedData = array();            
            $nestedData['actions'] = $actions;
            $nestedData['name'] = $data->name;
            $nestedData['date'] = $data->date;
            $nestedData['description'] = '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->description.'">'. $data->description .'</span>';
            $nestedData['type'] = '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->type.'">'. $data->type .'</span>';
            $nestedData['debit'] = number_format($data->debit,2,'.','');
            $nestedData['credit'] = number_format($data->credit,2,'.','');
            $nestedData['bal'] = $data->bal;
            $nestedData['created_at'] = $this->dateFormat($data->created_at,1);
            $rows[] = $nestedData;

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function bstRadioSelect(Request $request)
    {

        if(!empty($request->get('id')) && !empty($request->get('type')))
        {
            Manualselecteddata::where("table_type" ,$request->get('type'))->where("manual_selected_data.user_id",Auth::user()->id)->delete();

            $bstObj = new Manualselecteddata();

            $bstObj->related_table_id = $request->get('id');
            $bstObj->table_type = $request->get('type');
            $bstObj->user_id = Auth::user()->id;

            $bstObj->save();

            echo "success";
        }
        else
        {
            echo "error";
        }
        exit;
    }

    public function txnSelect(Request $request)
    {

        if(!empty($request->get('id')) && !empty($request->get('type')))
        {
            $bstObj = new Manualselecteddata();

            $bstObj->related_table_id = $request->get('id');
            $bstObj->table_type = $request->get('type');
            $bstObj->user_id = Auth::user()->id;

            $bstObj->save();

            echo "success";
        }
        else
        {
            echo "error";
        }
        exit;
    }

    public function bstRadioDelete(Request $request)
    {
        if(!empty($request->get('id')) && !empty($request->get('type')))
        {
            Manualselecteddata::where("table_type" ,$request->get('type'))->where("manual_selected_data.user_id",Auth::user()->id)->delete();

            echo "success";
        }
        else
        {
            echo "error";
        }
        exit;
    }

    public function transactionDelete(Request $request)
    {
        if(!empty($request->get('id')) && !empty($request->get('type')))
        {
            Manualselecteddata::where("table_type" ,$request->get('type'))->where("related_table_id" ,$request->get('id'))->where("manual_selected_data.user_id",Auth::user()->id)->delete();

            echo "success";
        }
        else
        {
            echo "error";
        }
        exit;
    }

    public function filterTransaction(Request $request)
    {
        if(!empty($request->get('TransactionDrop')) && !empty($request->get('txn_start_date')) && !empty($request->get('txn_end_date')))
        {
            $filterData = array();
            $filterData['TransactionDrop'] = $request->get('TransactionDrop');
            $filterData['txn_start_date'] = $request->get('txn_start_date');
            $filterData['txn_end_date'] = $request->get('txn_end_date');

            session(['txnfilter'=>$filterData]);


            if($request->get('TransactionDrop') == "agencybanking_approved")
            {
                return View::make('manualtransaction/txnhtml');
            }
            else if($request->get('TransactionDrop') == "agencybanking_declined")
            {
                return View::make('manualtransaction/abdeclinedtxnhtml');
            }
            else if($request->get('TransactionDrop') == "fp_out")
            {
                return View::make('manualtransaction/fpouttxnhtml');
            }   
            else if($request->get('TransactionDrop') == "balance_adj")
            {
                return View::make('manualtransaction/balanceadjhtml');
            }   
        }
    }

    public function getAbdDatatable(Request $request)
    {
        $fData = $request->session()->get('txnfilter');
        $params = array();

        if(!empty($fData))
        {
            $params['start_date'] = date("Y-m-d",strtotime($fData['txn_start_date']));
            $params['end_date'] = date("Y-m-d",strtotime($fData['txn_end_date']));
        }

        $result = Agencybanking::getAbdDatatableData($params);    
        $selectedBstVal = Manualselecteddata::where("table_type" , 'agencybanking_approved')->where("manual_selected_data.user_id",Auth::user()->id)->pluck("related_table_id","id")->toArray();   
        $rows = array();

        foreach ($result['data'] as $agencybanking) {

            if(in_array($agencybanking->ids, $selectedBstVal))
            {
                $actions = '<input type="checkbox" name="chkbox" id="txnchkbox-'.$agencybanking->ids.'" class="selectchk radioSelected" value="'.$agencybanking->ids.'" onchange="txnCheckBoxSelect(this, \''.$agencybanking->ids.'\' , \'agencybanking_approved\')" checked="true">';
            }
            else
            {
                $actions = '<input type="checkbox" name="chkboc" id="txnchkbox-'.$agencybanking->ids.'" class="selectchk" value="'.$agencybanking->ids.'" onchange="txnCheckBoxSelect(this, \''.$agencybanking->ids.'\' , \'agencybanking_approved\')">';
            }

            $rows[] = array(
                'actions' => $actions,
                'CashType'    => e($agencybanking->CashType),                
                'BankingId'   => e($agencybanking->BankingId),
                'SettlementDate'  => e($agencybanking->SettlementDate),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->Desc.'">'. $agencybanking->Desc .'</span>',
                'Card_PAN'  => e($agencybanking->Card_PAN),                
                'AgencyAccount_no'    => e($agencybanking->AgencyAccount_no),                
                'AgencyAccount_sortcode'  => e($agencybanking->AgencyAccount_sortcode),                                           
                'External_sortcode'  => e($agencybanking->External_sortcode),                                           
                'External_bankacc'  => e($agencybanking->External_bankacc),                                           
                'External_name'  => e($agencybanking->External_name),                                           
                'CashAmt_value'  => number_format(e($agencybanking->CashAmt_value),2,'.',''),                                           
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

                'File_filename'  => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->File_filename.'">'. $agencybanking->File_filename .'</span>',                                           
                'File_filedate'  => (!empty($agencybanking->File_filedate))? date("Y-m-d H:i:s",strtotime(e($agencybanking->File_filedate))) : "-",
                
                'file_date'  => e($agencybanking->file_date),
                "file_name" => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->file_name.'">'. $agencybanking->file_name .'</span>',                                             
            );

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function getAbDeclinedDatatable(Request $request)
    {
        $fData = $request->session()->get('txnfilter');
        $params = array();

        if(!empty($fData))
        {
            $params['start_date'] = date("Y-m-d",strtotime($fData['txn_start_date']));
            $params['end_date'] = date("Y-m-d",strtotime($fData['txn_end_date']));
        }

        $result = Agencybanking::getAbDeclinedDatatable($params);    
        $selectedBstVal = Manualselecteddata::where("table_type" , 'agencybanking_declined')->where("manual_selected_data.user_id",Auth::user()->id)->pluck("related_table_id","id")->toArray();   
        $rows = array();

        foreach ($result['data'] as $agencybanking) {

            if(in_array($agencybanking->ids, $selectedBstVal))
            {
                $actions = '<input type="checkbox" name="chkbox" id="txnchkbox-'.$agencybanking->ids.'" class="selectchk radioSelected" value="'.$agencybanking->ids.'" onchange="txnCheckBoxSelect(this, \''.$agencybanking->ids.'\' , \'agencybanking_declined\')" checked="true">';
            }
            else
            {
                $actions = '<input type="checkbox" name="chkboc" id="txnchkbox-'.$agencybanking->ids.'" class="selectchk" value="'.$agencybanking->ids.'" onchange="txnCheckBoxSelect(this, \''.$agencybanking->ids.'\' , \'agencybanking_declined\')">';
            }

            $rows[] = array(
                'actions' => $actions,
                'CashType'    => e($agencybanking->CashType),                
                'BankingId'   => e($agencybanking->BankingId),
                'SettlementDate'  => e($agencybanking->SettlementDate),
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->Desc.'">'. $agencybanking->Desc .'</span>',
                'Card_PAN'  => e($agencybanking->Card_PAN),                
                'AgencyAccount_no'    => e($agencybanking->AgencyAccount_no),                
                'AgencyAccount_sortcode'  => e($agencybanking->AgencyAccount_sortcode),                                           
                'External_sortcode'  => e($agencybanking->External_sortcode),                                           
                'External_bankacc'  => e($agencybanking->External_bankacc),                                           
                'External_name'  => e($agencybanking->External_name),                                           
                'CashAmt_value'  => number_format(e($agencybanking->CashAmt_value),2,'.',''),                                           
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

                'File_filename'  => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->File_filename.'">'. $agencybanking->File_filename .'</span>',                                           
                'File_filedate'  => (!empty($agencybanking->File_filedate))? date("Y-m-d H:i:s",strtotime(e($agencybanking->File_filedate))) : "-",
                'file_date'  => e($agencybanking->file_date),
                "file_name" => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$agencybanking->file_name.'">'. $agencybanking->file_name .'</span>',                          
                                                       
            );

        }

        return array('total'=>$result['count'], 'rows'=>$rows);  
    }

    public function getFpoutDatatable(Request $request)
    {
        $fData = $request->session()->get('txnfilter');
        $params = array();

        if(!empty($fData))
        {
            $params['start_date'] = date("Y-m-d",strtotime($fData['txn_start_date']));
            $params['end_date'] = date("Y-m-d",strtotime($fData['txn_end_date']));
        }

        $result = Fpout::getFpoutCmpDatatableData($params);
        $selectedBstVal = Manualselecteddata::where("table_type" , 'fp_out')->where("manual_selected_data.user_id",Auth::user()->id)->pluck("related_table_id","id")->toArray();
        
        $rows = array();
        foreach ($result['data'] as $data) {

            if(in_array($data->ids, $selectedBstVal))
            {
                $actions = '<input type="checkbox" name="chkbox" id="txnchkbox-'.$data->ids.'" class="selectchk radioSelected" value="'.$data->ids.'" onchange="txnFpoutCheckBoxSelect(this, \''.$data->ids.'\' , \'fp_out\')" checked="true">';
            }
            else
            {
                $actions = '<input type="checkbox" name="chkboc" id="txnchkbox-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" onchange="txnFpoutCheckBoxSelect(this, \''.$data->ids.'\' , \'fp_out\')">';
            }

            $rows[] = array(
                'actions' => $actions,
                'FileID'    => e($data->FileID),                
                'FPID'   => e($data->FPID),
                'OrigCustomerSortCode'  => e($data->OrigCustomerSortCode),                
                'OrigCustomerAccountNumber'  => e($data->OrigCustomerAccountNumber),                
                'BeneficiaryCreditInstitution'    => e($data->BeneficiaryCreditInstitution),                
                'BeneficiaryCustomerAccountNumber'    => e($data->BeneficiaryCustomerAccountNumber),                
                'Amount'  => number_format(e($data->Amount),2,'.',''),              
                'ProcessedAsynchronously'  => e($data->ProcessedAsynchronously),                                           
                'ReferenceInformation'  => e($data->ReferenceInformation),             
                'OrigCustomerAccountName'  => round(e($data->OrigCustomerAccountName),2),                                           
                'file_date'  => e($data->file_date),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows); 
    }

    public function submitMatches(Request $request)
    {
        $user_Id = Auth::user()->id;
        $setBstRecoData = Bankstatement::select('bank_statement.*' , 'bank_statement.id as ids')->join("manual_selected_data","manual_selected_data.related_table_id","=","bank_statement.id")->where("table_type","bank_statement")->where("manual_selected_data.user_id",Auth::user()->id)->first();

        if(!empty($setBstRecoData))
        {
            if(!empty($request->get('extra_flg')))
            {
                $setBstRecoData->reco_flg = 'Y';
                $setBstRecoData->extra_flags = $request->get('extra_flg');
                $setBstRecoData->save();

            }
            else
            {
               
                $setRecoData = Manualselecteddata::select('manual_selected_data.*')->where("manual_selected_data.user_id",Auth::user()->id)->where("table_type","!=","bank_statement")->get();

                if(count($setRecoData) != 0)
                {
                    $setBstRecoData->reco_flg = 'Y';
                    $setBstRecoData->save();

                    foreach ($setRecoData as $key => $value) 
                    {
                        if($value->table_type == "agencybanking_approved" || $value->table_type == "agencybanking_declined")
                        {
                            Agencybanking::where("id",$value->related_table_id)->update([
                                "reco_flg" => "Y",
                                "reco_date" => $setBstRecoData->date
                            ]);

                            $TxnmappingintObj = new Txnmappingint();
                            $TxnmappingintObj->id = Helper::generateUniqueId();
                            $TxnmappingintObj->bank_statement_id = $setBstRecoData->ids;
                            $TxnmappingintObj->txn_type = "Agencybanking";
                            $TxnmappingintObj->txn_table_id = $value->related_table_id;
                            $TxnmappingintObj->coding = "Manual Compare";
                            $TxnmappingintObj->comment = "Manual Compare";
                            $TxnmappingintObj->created_by = $user_Id;

                            $TxnmappingintObj->save();

                        }

                        if($value->table_type == "fp_out")
                        {
                            Fpout::where("id",$value->related_table_id)->update([
                                "reco_flg" => "Y",
                                "reco_date" => $setBstRecoData->date
                            ]);

                            $TxnmappingintObj = new Txnmappingint();

                            $TxnmappingintObj->id = Helper::generateUniqueId();
                            $TxnmappingintObj->bank_statement_id = $setBstRecoData->ids;
                            $TxnmappingintObj->txn_type = "Fpout";
                            $TxnmappingintObj->txn_table_id = $value->related_table_id;
                            $TxnmappingintObj->coding = "Manual Compare";
                            $TxnmappingintObj->comment = "Manual Compare";
                            $TxnmappingintObj->created_by = $user_Id;

                            $TxnmappingintObj->save();

                        }

                        if($value->table_type == "balance_adj")
                        {
                            Cardbaladjust::where("id",$value->related_table_id)->update([
                                "reco_flg" => "Y",
                                "reco_date" => $setBstRecoData->date
                            ]);

                            $TxnmappingintObj = new Txnmappingint();

                            $TxnmappingintObj->id = Helper::generateUniqueId();
                            $TxnmappingintObj->bank_statement_id = $setBstRecoData->ids;
                            $TxnmappingintObj->txn_type = "Cardbaladjust";
                            $TxnmappingintObj->txn_table_id = $value->related_table_id;
                            $TxnmappingintObj->coding = "Manual Compare";
                            $TxnmappingintObj->comment = "Manual Compare";
                            $TxnmappingintObj->created_by = $user_Id;

                            $TxnmappingintObj->save();

                        }
                    }
                }
            }
                Manualselecteddata::where("user_id",Auth::user()->id)->delete();
        }
        
    }

    public function getBalanceAdjDatatable(Request $request)
    {
        $fData = $request->session()->get('txnfilter');
        $params = array();

        if(!empty($fData))
        {
            $params['start_date'] = date("Y-m-d",strtotime($fData['txn_start_date']));
            $params['end_date'] = date("Y-m-d",strtotime($fData['txn_end_date']));
        }

        $result = Cardbaladjust::getManualDatatableData($params);
        $selectedBstVal = Manualselecteddata::where("table_type" , 'balance_adj')->where("manual_selected_data.user_id",Auth::user()->id)->pluck("related_table_id","id")->toArray();

        $rows = array();
        foreach ($result['data'] as $data) {

            if(in_array($data->ids, $selectedBstVal))
            {
                $actions = '<input type="checkbox" name="chkbox" id="txnchkbox-'.$data->ids.'" class="selectchk radioSelected" value="'.$data->ids.'" onchange="txnBalAdjCheckBoxSelect(this, \''.$data->ids.'\' , \'balance_adj\')" checked="true">';
            }
            else
            {
                $actions = '<input type="checkbox" name="chkboc" id="txnchkbox-'.$data->ids.'" class="selectchk" value="'.$data->ids.'" onchange="txnBalAdjCheckBoxSelect(this, \''.$data->ids.'\' , \'balance_adj\')">';
            }

            $rows[] = array(
                'actions' => $actions,
                'SettlementDate'  => e($data->SettlementDate),                
                'Account_no'    => e($data->Account_no),                
                'Amount_value'  => round(e($data->Amount_value),2),                                                           
                'Desc'   => '<span style="display:block; white-space:nowrap; word-break:break-word;" data-toggle="tooltip" data-original-title="'.$data->Desc.'">'. $data->Desc .'</span>',          
                'RecType'    => e($data->RecType),                
                'AdjustId'   => e($data->AdjustId),
                'Card_PAN'  => e($data->Card_PAN),                
                'Amount_direction'  => e($data->Amount_direction),                                           
                'created_at'  => date('Y-m-d',strtotime(e($data->created_at))),                                           
                'file_date'  => e($data->file_date),                                           
            );             
        }

        return array('total'=>$result['count'], 'rows'=>$rows);
    }

    public function manualComparedTransaction(Request $request)
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

        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        
        $filterColumn[]=array("filter" => array("type" => "input"));        

        return View::make('manualtransaction/comparedtxn')->with('filterColumn', $filterColumn);
    }

    public function resetTxn(Request $request)
    {
        DB::table('manual_selected_data')->where("manual_selected_data.user_id",Auth::user()->id)->delete();

        return redirect()->to("manualtransaction/comparetxn"); 
    }

    public function manualComparedTransactionTable()
    {
        $params = array();
        if(Input::has('start_date') ){
            $params['start_date'] = $this->dateFormat(Input::get('start_date'),0);    
        }
        if(Input::has('end_date')){
            $params['end_date'] = $this->dateFormat(Input::get('end_date'),0);    
        }
        $result = Txnmappingint::getManualComparedData($params);               
        
        $rows = array();
        foreach ($result['data'] as $data) {

            $recoFlgHtml = '';
            $recoSattelDate = '';
            if($data->reco_flg == 'Y')
            {
                if(!empty($data->agencybankings[0]))
                {
                    if($data->date > $data->agencybankings['0']->SettlementDate)
                    {
                        $recoFlgHtml = '<span class="recoYesYellow">'.e($data->reco_flg).'</span>';
                    }
                    else
                    {
                        $recoFlgHtml = '<span class="recoYes">'.e($data->reco_flg).'</span>';                        
                    }
                    $recoSattelDate = $data->agencybankings['0']->SettlementDate;
                }
                else
                {
                    $recoFlgHtml = '<span class="recoYes">'.e($data->reco_flg).'</span>';
                }

            }
            else
            {
                $recoFlgHtml = '<span class="">'.e($data->reco_flg).'</span>';
            }
            $action = '';
            $action = "<button type='button' class='btn  btn-sm bg-blue' onclick='fetchRelatedData(\"".$data->ids."\")'><span class='fa fa-list'></span> </button>";

            $action .= "<button type='button' onclick='removeMatchTxn(\"".$data->ids."\")'  class='btn btn-sm bg-red' data-original-title='Remove transactions match' data-tooltip='tooltip'><span class='fa fa-remove'></span> </button>";

            $nestedData = array(); 
            $nestedData['action'] = $action;           
            $nestedData['reco_date'] = $recoSattelDate;
            $nestedData['name'] = $data->name;
            $nestedData['date'] = $data->date;
            $nestedData['description'] = $data->description;
            $nestedData['type'] = $data->type;
            $nestedData['debit'] = round($data->debit,2);
            $nestedData['credit'] = round($data->credit,2);
            $nestedData['bal'] = $data->bal;
            $nestedData['created_at'] = $this->dateFormat($data->created_at,1);
            $rows[] = $nestedData;

        }

        return array('total'=>$result['count'], 'rows'=>$rows);         
    }

    public function unMatchTransaction(Request $request)
    {
        if(!empty($request->get('bstId')))
        {
            $bstIntData = Txnmappingint::fetchAllTxnByBstId($request->get('bstId'));

            $bstData = Bankstatement::where("id",$request->get('bstId'))->first();

            $bstData->reco_flg = "N";
            $bstData->save();

            foreach ($bstIntData as $key => $value) {
                if($value->txn_type == "FP_Out")
                {
                    Fpout::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }

                if($value->txn_type == "AB Approved" || $value->txn_type == "AB Declined" || $value->txn_type == "DDR_Bacs")
                {
                    Agencybanking::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }

                if($value->txn_type == "DD")
                {
                    Directdebits::where("id",$value->txn_table_id)->update([
                        "reco_flg" => "N",
                        "reco_date" => NULL
                    ]);
                }
            }

            Txnmappingint::where("bank_statement_id",$request->get('bstId'))->delete();

            echo "success";  
        }
        else
        {
            echo "error";
        }
        exit;

    }


    public function fetchRelatedData(Request $request)
    {
        if(!empty(Input::get('bstId')))
        {
            $agencybankingApprovedData = array();
            $agencybankingApprovedData = Agencybanking::select("agencybanking.*","agencybanking.id as ids" , DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS txn_data_type"))
                ->join("txn_mapping_int","txn_mapping_int.txn_table_id","=","agencybanking.id")
                ->where("txn_mapping_int.bank_statement_id",Input::get('bstId'))
                ->where("txn_mapping_int.txn_type","Agencybanking")
                ->get();


            $fp_outData = array();
            $fp_outData = Fpout::select("fp_out.*","fp_out.id as ids")
                         ->join("txn_mapping_int","txn_mapping_int.txn_table_id","=","fp_out.id")
                        ->where("txn_mapping_int.txn_type","Fpout")
                        ->where("txn_mapping_int.bank_statement_id",Input::get('bstId'))
                        ->get();

            return View::make('manualtransaction/relatedtxndata')
                ->with('agencybankingApprovedData', $agencybankingApprovedData)
                ->with('fp_outData', $fp_outData)
                ;
        }
        else
        {
            echo "error";
            exit;
        }
    }
}
