<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardauthorisation extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardauthorisation';

    protected $rules = array(
        'AuthId'   => 'required',
        'RecType'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardauthorisation = Cardauthorisation::select('cardauthorisation.*');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardauthorisation = $cardauthorisation->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

        if (Input::has('offset')) {
            $offset = e(Input::get('offset'));
        } else {
            $offset = 0;
        }

        if (Input::has('limit')) {
            $limit = e(Input::get('limit'));
        } else {
            $limit = 50;
        }

        $sort = e(Input::get('sort'));        

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
            $cardauthorisation = $cardauthorisation->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardauthorisation = $cardauthorisation->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['RecType','AuthId','SettlementDate','Card_PAN','Account_no','TxnCode_direction','TxnCode_Type','BillAmt_value','BillAmt_rate','created_at','file_date','file_name','LocalDate','ApprCode','MerchCode','Schema','ReversalReason','Card_product','Card_programId','Card_branchCode','Card_productid','Account_type','TxnCode_Group','TxnCode_ProcCode','TxnAmt_value','TxnAmt_currency','CashbackAmt_value','CashbackAmt_currency','BillAmt_currency','BillAmt_clientfxrate','Trace_auditno','Trace_origauditno','Trace_Retrefno','Term_code','Term_location','Term_street','Term_city','Term_country','Term_inputcapability','Term_authcapability','Txn_cardholderpresent','Txn_cardpresent','Txn_cardinputmethod','Txn_cardauthmethod','Txn_cardauthentity','Txn_TVR','MsgSource_value','MsgSource_domesticMaestro','PaddingAmt_value','PaddingAmt_currency','CommissionAmt_value','CommissionAmt_currency','Classification_RCC','Classification_MCC','Response_approved','Response_actioncode','Response_responsecode','OrigTxnAmt_value','OrigTxnAmt_currency','OrigTxnAmt_origItemId','OrigTxnAmt_partial'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardauthorisation.created_at';

        $cardauthorisation = $cardauthorisation->orderBy($sort, $order);
        
        $caCount = $cardauthorisation->count();
        // $caCount = count($caCount);

        if($limit != 0){            
            $cardauthorisation = $cardauthorisation->skip($offset)->take($limit)->get();
        }
        else{   
            $cardauthorisation = $cardauthorisation->get();
        }
     
        return array(
            'data' => $cardauthorisation,
            'count' => $caCount
        );
    }

    /**
     * Query builder scope to search on text
     *
     * @param  Illuminate\Database\Query\Builder  $query  Query builder instance
     * @param  text                              $search      Search term
     *
     * @return Illuminate\Database\Query\Builder          Modified query builder
     */
    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['RecType']) && !empty($filterArray['RecType']))
                {
                    $query->where('RecType', 'LIKE', '%'.$filterArray['RecType'].'%');
                }

                if(isset($filterArray['AuthId']) && !empty($filterArray['AuthId']))
                {
                    $query->where('AuthId', 'LIKE', '%'.$filterArray['AuthId'].'%');
                }

                if(isset($filterArray['SettlementDate']) && !empty($filterArray['SettlementDate']))
                {
                    $query->where('SettlementDate', 'LIKE', '%'.$filterArray['SettlementDate'].'%');
                }

                if(isset($filterArray['Card_PAN']) && !empty($filterArray['Card_PAN']))
                {
                    $query->where('Card_PAN', 'LIKE', '%'.$filterArray['Card_PAN'].'%');
                }

                if(isset($filterArray['Account_no']) && !empty($filterArray['Account_no']))
                {
                    $query->where('Account_no', 'LIKE', '%'.$filterArray['Account_no'].'%');
                }

                if(isset($filterArray['TxnCode_direction']) && !empty($filterArray['TxnCode_direction']))
                {
                    $query->where('TxnCode_direction', 'LIKE', '%'.$filterArray['TxnCode_direction'].'%');
                }

                if(isset($filterArray['TxnCode_Type']) && !empty($filterArray['TxnCode_Type']))
                {
                    $query->where('TxnCode_Type', 'LIKE', '%'.$filterArray['TxnCode_Type'].'%');
                }

                if(isset($filterArray['BillAmt_value']) && !empty($filterArray['BillAmt_value']))
                {
                    $query->where('BillAmt_value', 'LIKE', '%'.$filterArray['BillAmt_value'].'%');
                }

                if(isset($filterArray['BillAmt_rate']) && !empty($filterArray['BillAmt_rate']))
                {
                    $query->where('BillAmt_rate', 'LIKE', '%'.$filterArray['BillAmt_rate'].'%');
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
                }

                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }

                if(isset($filterArray["file_name"]) && !empty($filterArray["file_name"])) { $query->where("file_name", "LIKE", "%".$filterArray["file_name"]."%"); }
                if(isset($filterArray["LocalDate"]) && !empty($filterArray["LocalDate"])) { $query->where("LocalDate", "LIKE", "%".$filterArray["LocalDate"]."%"); }
                if(isset($filterArray["ApprCode"]) && !empty($filterArray["ApprCode"])) { $query->where("ApprCode", "LIKE", "%".$filterArray["ApprCode"]."%"); }
                if(isset($filterArray["MerchCode"]) && !empty($filterArray["MerchCode"])) { $query->where("MerchCode", "LIKE", "%".$filterArray["MerchCode"]."%"); }
                if(isset($filterArray["Schema"]) && !empty($filterArray["Schema"])) { $query->where("Schema", "LIKE", "%".$filterArray["Schema"]."%"); }
                if(isset($filterArray["ReversalReason"]) && !empty($filterArray["ReversalReason"])) { $query->where("ReversalReason", "LIKE", "%".$filterArray["ReversalReason"]."%"); }
                if(isset($filterArray["Card_product"]) && !empty($filterArray["Card_product"])) { $query->where("Card_product", "LIKE", "%".$filterArray["Card_product"]."%"); }
                if(isset($filterArray["Card_programId"]) && !empty($filterArray["Card_programId"])) { $query->where("Card_programId", "LIKE", "%".$filterArray["Card_programId"]."%"); }
                if(isset($filterArray["Card_branchCode"]) && !empty($filterArray["Card_branchCode"])) { $query->where("Card_branchCode", "LIKE", "%".$filterArray["Card_branchCode"]."%"); }
                if(isset($filterArray["Card_productid"]) && !empty($filterArray["Card_productid"])) { $query->where("Card_productid", "LIKE", "%".$filterArray["Card_productid"]."%"); }
                if(isset($filterArray["Account_type"]) && !empty($filterArray["Account_type"])) { $query->where("Account_type", "LIKE", "%".$filterArray["Account_type"]."%"); }
                if(isset($filterArray["TxnCode_Group"]) && !empty($filterArray["TxnCode_Group"])) { $query->where("TxnCode_Group", "LIKE", "%".$filterArray["TxnCode_Group"]."%"); }
                if(isset($filterArray["TxnCode_ProcCode"]) && !empty($filterArray["TxnCode_ProcCode"])) { $query->where("TxnCode_ProcCode", "LIKE", "%".$filterArray["TxnCode_ProcCode"]."%"); }
                if(isset($filterArray["TxnAmt_value"]) && !empty($filterArray["TxnAmt_value"])) { $query->where("TxnAmt_value", "LIKE", "%".$filterArray["TxnAmt_value"]."%"); }
                if(isset($filterArray["TxnAmt_currency"]) && !empty($filterArray["TxnAmt_currency"])) { $query->where("TxnAmt_currency", "LIKE", "%".$filterArray["TxnAmt_currency"]."%"); }
                if(isset($filterArray["CashbackAmt_value"]) && !empty($filterArray["CashbackAmt_value"])) { $query->where("CashbackAmt_value", "LIKE", "%".$filterArray["CashbackAmt_value"]."%"); }
                if(isset($filterArray["CashbackAmt_currency"]) && !empty($filterArray["CashbackAmt_currency"])) { $query->where("CashbackAmt_currency", "LIKE", "%".$filterArray["CashbackAmt_currency"]."%"); }
                if(isset($filterArray["BillAmt_currency"]) && !empty($filterArray["BillAmt_currency"])) { $query->where("BillAmt_currency", "LIKE", "%".$filterArray["BillAmt_currency"]."%"); }
                if(isset($filterArray["BillAmt_clientfxrate"]) && !empty($filterArray["BillAmt_clientfxrate"])) { $query->where("BillAmt_clientfxrate", "LIKE", "%".$filterArray["BillAmt_clientfxrate"]."%"); }
                if(isset($filterArray["Trace_auditno"]) && !empty($filterArray["Trace_auditno"])) { $query->where("Trace_auditno", "LIKE", "%".$filterArray["Trace_auditno"]."%"); }
                if(isset($filterArray["Trace_origauditno"]) && !empty($filterArray["Trace_origauditno"])) { $query->where("Trace_origauditno", "LIKE", "%".$filterArray["Trace_origauditno"]."%"); }
                if(isset($filterArray["Trace_Retrefno"]) && !empty($filterArray["Trace_Retrefno"])) { $query->where("Trace_Retrefno", "LIKE", "%".$filterArray["Trace_Retrefno"]."%"); }
                if(isset($filterArray["Term_code"]) && !empty($filterArray["Term_code"])) { $query->where("Term_code", "LIKE", "%".$filterArray["Term_code"]."%"); }
                if(isset($filterArray["Term_location"]) && !empty($filterArray["Term_location"])) { $query->where("Term_location", "LIKE", "%".$filterArray["Term_location"]."%"); }
                if(isset($filterArray["Term_street"]) && !empty($filterArray["Term_street"])) { $query->where("Term_street", "LIKE", "%".$filterArray["Term_street"]."%"); }
                if(isset($filterArray["Term_city"]) && !empty($filterArray["Term_city"])) { $query->where("Term_city", "LIKE", "%".$filterArray["Term_city"]."%"); }
                if(isset($filterArray["Term_country"]) && !empty($filterArray["Term_country"])) { $query->where("Term_country", "LIKE", "%".$filterArray["Term_country"]."%"); }
                if(isset($filterArray["Term_inputcapability"]) && !empty($filterArray["Term_inputcapability"])) { $query->where("Term_inputcapability", "LIKE", "%".$filterArray["Term_inputcapability"]."%"); }
                if(isset($filterArray["Term_authcapability"]) && !empty($filterArray["Term_authcapability"])) { $query->where("Term_authcapability", "LIKE", "%".$filterArray["Term_authcapability"]."%"); }
                if(isset($filterArray["Txn_cardholderpresent"]) && !empty($filterArray["Txn_cardholderpresent"])) { $query->where("Txn_cardholderpresent", "LIKE", "%".$filterArray["Txn_cardholderpresent"]."%"); }
                if(isset($filterArray["Txn_cardpresent"]) && !empty($filterArray["Txn_cardpresent"])) { $query->where("Txn_cardpresent", "LIKE", "%".$filterArray["Txn_cardpresent"]."%"); }
                if(isset($filterArray["Txn_cardinputmethod"]) && !empty($filterArray["Txn_cardinputmethod"])) { $query->where("Txn_cardinputmethod", "LIKE", "%".$filterArray["Txn_cardinputmethod"]."%"); }
                if(isset($filterArray["Txn_cardauthmethod"]) && !empty($filterArray["Txn_cardauthmethod"])) { $query->where("Txn_cardauthmethod", "LIKE", "%".$filterArray["Txn_cardauthmethod"]."%"); }
                if(isset($filterArray["Txn_cardauthentity"]) && !empty($filterArray["Txn_cardauthentity"])) { $query->where("Txn_cardauthentity", "LIKE", "%".$filterArray["Txn_cardauthentity"]."%"); }
                if(isset($filterArray["Txn_TVR"]) && !empty($filterArray["Txn_TVR"])) { $query->where("Txn_TVR", "LIKE", "%".$filterArray["Txn_TVR"]."%"); }
                if(isset($filterArray["MsgSource_value"]) && !empty($filterArray["MsgSource_value"])) { $query->where("MsgSource_value", "LIKE", "%".$filterArray["MsgSource_value"]."%"); }
                if(isset($filterArray["MsgSource_domesticMaestro"]) && !empty($filterArray["MsgSource_domesticMaestro"])) { $query->where("MsgSource_domesticMaestro", "LIKE", "%".$filterArray["MsgSource_domesticMaestro"]."%"); }
                if(isset($filterArray["PaddingAmt_value"]) && !empty($filterArray["PaddingAmt_value"])) { $query->where("PaddingAmt_value", "LIKE", "%".$filterArray["PaddingAmt_value"]."%"); }
                if(isset($filterArray["PaddingAmt_currency"]) && !empty($filterArray["PaddingAmt_currency"])) { $query->where("PaddingAmt_currency", "LIKE", "%".$filterArray["PaddingAmt_currency"]."%"); }
                if(isset($filterArray["CommissionAmt_value"]) && !empty($filterArray["CommissionAmt_value"])) { $query->where("CommissionAmt_value", "LIKE", "%".$filterArray["CommissionAmt_value"]."%"); }
                if(isset($filterArray["CommissionAmt_currency"]) && !empty($filterArray["CommissionAmt_currency"])) { $query->where("CommissionAmt_currency", "LIKE", "%".$filterArray["CommissionAmt_currency"]."%"); }
                if(isset($filterArray["Classification_RCC"]) && !empty($filterArray["Classification_RCC"])) { $query->where("Classification_RCC", "LIKE", "%".$filterArray["Classification_RCC"]."%"); }
                if(isset($filterArray["Classification_MCC"]) && !empty($filterArray["Classification_MCC"])) { $query->where("Classification_MCC", "LIKE", "%".$filterArray["Classification_MCC"]."%"); }
                if(isset($filterArray["Response_approved"]) && !empty($filterArray["Response_approved"])) { $query->where("Response_approved", "LIKE", "%".$filterArray["Response_approved"]."%"); }
                if(isset($filterArray["Response_actioncode"]) && !empty($filterArray["Response_actioncode"])) { $query->where("Response_actioncode", "LIKE", "%".$filterArray["Response_actioncode"]."%"); }
                if(isset($filterArray["Response_responsecode"]) && !empty($filterArray["Response_responsecode"])) { $query->where("Response_responsecode", "LIKE", "%".$filterArray["Response_responsecode"]."%"); }
                if(isset($filterArray["OrigTxnAmt_value"]) && !empty($filterArray["OrigTxnAmt_value"])) { $query->where("OrigTxnAmt_value", "LIKE", "%".$filterArray["OrigTxnAmt_value"]."%"); }
                if(isset($filterArray["OrigTxnAmt_currency"]) && !empty($filterArray["OrigTxnAmt_currency"])) { $query->where("OrigTxnAmt_currency", "LIKE", "%".$filterArray["OrigTxnAmt_currency"]."%"); }
                if(isset($filterArray["OrigTxnAmt_origItemId"]) && !empty($filterArray["OrigTxnAmt_origItemId"])) { $query->where("OrigTxnAmt_origItemId", "LIKE", "%".$filterArray["OrigTxnAmt_origItemId"]."%"); }
                if(isset($filterArray["OrigTxnAmt_partial"]) && !empty($filterArray["OrigTxnAmt_partial"])) { $query->where("OrigTxnAmt_partial", "LIKE", "%".$filterArray["OrigTxnAmt_partial"]."%"); }


            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('cardauthorisation.RecType', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.AuthId', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.SettlementDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.Account_no', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.TxnCode_direction', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.TxnCode_Type', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.BillAmt_value', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.BillAmt_rate', 'LIKE',"%{$search}%")
                        ->orWhere('cardauthorisation.created_at', 'LIKE',"%{$search}%")

                        ->orWhere("cardauthorisation.file_name", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.LocalDate", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.ApprCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.MerchCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Schema", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.ReversalReason", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Card_product", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Card_programId", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Card_branchCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Card_productid", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Account_type", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.TxnCode_Group", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.TxnCode_ProcCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.TxnAmt_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.TxnAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.CashbackAmt_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.CashbackAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.BillAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.BillAmt_clientfxrate", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Trace_auditno", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Trace_origauditno", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Trace_Retrefno", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_code", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_location", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_street", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_city", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_country", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_inputcapability", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Term_authcapability", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_cardholderpresent", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_cardpresent", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_cardinputmethod", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_cardauthmethod", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_cardauthentity", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Txn_TVR", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.MsgSource_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.MsgSource_domesticMaestro", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.PaddingAmt_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.PaddingAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.CommissionAmt_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.CommissionAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Classification_RCC", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Classification_MCC", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Response_approved", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Response_actioncode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.Response_responsecode", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.OrigTxnAmt_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.OrigTxnAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.OrigTxnAmt_origItemId", 'LIKE',"%{$search}%")
                        ->orWhere("cardauthorisation.OrigTxnAmt_partial", 'LIKE',"%{$search}%")




                        ->orWhere('cardauthorisation.file_date', 'LIKE',"%{$search}%");
                }                     
            });
        }
    }
}
