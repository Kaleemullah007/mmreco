<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardchrgbackrepres extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardchrgbackrepres';

    protected $rules = array(
        'ChgbackRepresId'   => 'required',
        'RecordType'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardchrgbackrepres = Cardchrgbackrepres::select('cardchrgbackrepres.*');             
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardchrgbackrepres = $cardchrgbackrepres->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardchrgbackrepres = $cardchrgbackrepres->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardchrgbackrepres = $cardchrgbackrepres->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['RecordType','ChgbackRepresId','LocalDate','SettlementDate','Card_PAN','Account_no','TxnCode_direction','TxnCode_Type','TxnAmt_value','CashbackAmt_value','BillAmt_value','Fee_direction','Fee_value','created_at','file_date','file_name','ApprCode','MerchCode','Schema','Repeat','ARN','FIID','RIID','ReasonCode','PartialReversal','Card_product','Card_programid','Card_branchcode','Card_productid','Account_type','TxnCode_Group','TxnAmt_currency','CashbackAmt_currency','BillAmt_currency','BillAmt_rate','Trace_auditno','Trace_origauditno','Trace_Retrefno','Term_code','Term_location','Term_street','Term_city','Term_country','Term_inputcapability','Term_authcapability','Txn_cardholderpresent','Txn_cardpresent','Txn_cardinputmethod','Txn_cardauthmethod','Txn_cardauthentity','Txn_TVR','MsgSource_value','MsgSource_domesticMaestro','SettlementAmt_value','SettlementAmt_currency','SettlementAmt_rate','SettlementAmt_date','Fee_currency','Classification_RCC','Classification_MCC','OrigTxnAmt_value','OrigTxnAmt_currency','OrigTxnAmt_origItemId','OrigTxnAmt_partial'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardchrgbackrepres.created_at';

        $cardchrgbackrepres = $cardchrgbackrepres->orderBy($sort, $order);
        
        $ccbrCount = $cardchrgbackrepres->count();
        // $ccbrCount = count($ccbrCount);

        if($limit != 0){            
            $cardchrgbackrepres = $cardchrgbackrepres->skip($offset)->take($limit)->get();
        }
        else{   
            $cardchrgbackrepres = $cardchrgbackrepres->get();
        }
     
        return array(
            'data' => $cardchrgbackrepres,
            'count' => $ccbrCount
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
                if(isset($filterArray['RecordType']) && !empty($filterArray['RecordType']))
                {
                    $query->where('RecordType', 'LIKE', '%'.$filterArray['RecordType'].'%');
                }

                if(isset($filterArray['ChgbackRepresId']) && !empty($filterArray['ChgbackRepresId']))
                {
                    $query->where('ChgbackRepresId', 'LIKE', '%'.$filterArray['ChgbackRepresId'].'%');
                }

                if(isset($filterArray['LocalDate']) && !empty($filterArray['LocalDate']))
                {
                    $query->where('LocalDate', 'LIKE', '%'.$filterArray['LocalDate'].'%');
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

                if(isset($filterArray['TxnAmt_value']) && !empty($filterArray['TxnAmt_value']))
                {
                    $query->where('TxnAmt_value', 'LIKE', '%'.$filterArray['TxnAmt_value'].'%');
                }

                if(isset($filterArray['CashbackAmt_value']) && !empty($filterArray['CashbackAmt_value']))
                {
                    $query->where('CashbackAmt_value', 'LIKE', '%'.$filterArray['CashbackAmt_value'].'%');
                }

                if(isset($filterArray['BillAmt_value']) && !empty($filterArray['BillAmt_value']))
                {
                    $query->where('BillAmt_value', 'LIKE', '%'.$filterArray['BillAmt_value'].'%');
                }

				if(isset($filterArray['Fee_direction']) && !empty($filterArray['Fee_direction']))
	            {
	                $query->where('Fee_direction', 'LIKE', '%'.$filterArray['Fee_direction'].'%');
	            }

	            if(isset($filterArray['Fee_value']) && !empty($filterArray['Fee_value']))
	            {
	                $query->where('Fee_value', 'LIKE', '%'.$filterArray['Fee_value'].'%');
	            }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
                }

                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }


                if(isset($filterArray["file_name"]) && !empty($filterArray["file_name"])){$query->where("file_name", "LIKE", "%".$filterArray["file_name"]."%");}
                if(isset($filterArray["ApprCode"]) && !empty($filterArray["ApprCode"])){$query->where("ApprCode", "LIKE", "%".$filterArray["ApprCode"]."%");}
                if(isset($filterArray["MerchCode"]) && !empty($filterArray["MerchCode"])){$query->where("MerchCode", "LIKE", "%".$filterArray["MerchCode"]."%");}
                if(isset($filterArray["Schema"]) && !empty($filterArray["Schema"])){$query->where("Schema", "LIKE", "%".$filterArray["Schema"]."%");}
                if(isset($filterArray["Repeat"]) && !empty($filterArray["Repeat"])){$query->where("Repeat", "LIKE", "%".$filterArray["Repeat"]."%");}
                if(isset($filterArray["ARN"]) && !empty($filterArray["ARN"])){$query->where("ARN", "LIKE", "%".$filterArray["ARN"]."%");}
                if(isset($filterArray["FIID"]) && !empty($filterArray["FIID"])){$query->where("FIID", "LIKE", "%".$filterArray["FIID"]."%");}
                if(isset($filterArray["RIID"]) && !empty($filterArray["RIID"])){$query->where("RIID", "LIKE", "%".$filterArray["RIID"]."%");}
                if(isset($filterArray["ReasonCode"]) && !empty($filterArray["ReasonCode"])){$query->where("ReasonCode", "LIKE", "%".$filterArray["ReasonCode"]."%");}
                if(isset($filterArray["PartialReversal"]) && !empty($filterArray["PartialReversal"])){$query->where("PartialReversal", "LIKE", "%".$filterArray["PartialReversal"]."%");}
                if(isset($filterArray["Card_product"]) && !empty($filterArray["Card_product"])){$query->where("Card_product", "LIKE", "%".$filterArray["Card_product"]."%");}
                if(isset($filterArray["Card_programid"]) && !empty($filterArray["Card_programid"])){$query->where("Card_programid", "LIKE", "%".$filterArray["Card_programid"]."%");}
                if(isset($filterArray["Card_branchcode"]) && !empty($filterArray["Card_branchcode"])){$query->where("Card_branchcode", "LIKE", "%".$filterArray["Card_branchcode"]."%");}
                if(isset($filterArray["Card_productid"]) && !empty($filterArray["Card_productid"])){$query->where("Card_productid", "LIKE", "%".$filterArray["Card_productid"]."%");}
                if(isset($filterArray["Account_type"]) && !empty($filterArray["Account_type"])){$query->where("Account_type", "LIKE", "%".$filterArray["Account_type"]."%");}
                if(isset($filterArray["TxnCode_Group"]) && !empty($filterArray["TxnCode_Group"])){$query->where("TxnCode_Group", "LIKE", "%".$filterArray["TxnCode_Group"]."%");}
                if(isset($filterArray["TxnAmt_currency"]) && !empty($filterArray["TxnAmt_currency"])){$query->where("TxnAmt_currency", "LIKE", "%".$filterArray["TxnAmt_currency"]."%");}
                if(isset($filterArray["CashbackAmt_currency"]) && !empty($filterArray["CashbackAmt_currency"])){$query->where("CashbackAmt_currency", "LIKE", "%".$filterArray["CashbackAmt_currency"]."%");}
                if(isset($filterArray["BillAmt_currency"]) && !empty($filterArray["BillAmt_currency"])){$query->where("BillAmt_currency", "LIKE", "%".$filterArray["BillAmt_currency"]."%");}
                if(isset($filterArray["BillAmt_rate"]) && !empty($filterArray["BillAmt_rate"])){$query->where("BillAmt_rate", "LIKE", "%".$filterArray["BillAmt_rate"]."%");}
                if(isset($filterArray["Trace_auditno"]) && !empty($filterArray["Trace_auditno"])){$query->where("Trace_auditno", "LIKE", "%".$filterArray["Trace_auditno"]."%");}
                if(isset($filterArray["Trace_origauditno"]) && !empty($filterArray["Trace_origauditno"])){$query->where("Trace_origauditno", "LIKE", "%".$filterArray["Trace_origauditno"]."%");}
                if(isset($filterArray["Trace_Retrefno"]) && !empty($filterArray["Trace_Retrefno"])){$query->where("Trace_Retrefno", "LIKE", "%".$filterArray["Trace_Retrefno"]."%");}
                if(isset($filterArray["Term_code"]) && !empty($filterArray["Term_code"])){$query->where("Term_code", "LIKE", "%".$filterArray["Term_code"]."%");}
                if(isset($filterArray["Term_location"]) && !empty($filterArray["Term_location"])){$query->where("Term_location", "LIKE", "%".$filterArray["Term_location"]."%");}
                if(isset($filterArray["Term_street"]) && !empty($filterArray["Term_street"])){$query->where("Term_street", "LIKE", "%".$filterArray["Term_street"]."%");}
                if(isset($filterArray["Term_city"]) && !empty($filterArray["Term_city"])){$query->where("Term_city", "LIKE", "%".$filterArray["Term_city"]."%");}
                if(isset($filterArray["Term_country"]) && !empty($filterArray["Term_country"])){$query->where("Term_country", "LIKE", "%".$filterArray["Term_country"]."%");}
                if(isset($filterArray["Term_inputcapability"]) && !empty($filterArray["Term_inputcapability"])){$query->where("Term_inputcapability", "LIKE", "%".$filterArray["Term_inputcapability"]."%");}
                if(isset($filterArray["Term_authcapability"]) && !empty($filterArray["Term_authcapability"])){$query->where("Term_authcapability", "LIKE", "%".$filterArray["Term_authcapability"]."%");}
                if(isset($filterArray["Txn_cardholderpresent"]) && !empty($filterArray["Txn_cardholderpresent"])){$query->where("Txn_cardholderpresent", "LIKE", "%".$filterArray["Txn_cardholderpresent"]."%");}
                if(isset($filterArray["Txn_cardpresent"]) && !empty($filterArray["Txn_cardpresent"])){$query->where("Txn_cardpresent", "LIKE", "%".$filterArray["Txn_cardpresent"]."%");}
                if(isset($filterArray["Txn_cardinputmethod"]) && !empty($filterArray["Txn_cardinputmethod"])){$query->where("Txn_cardinputmethod", "LIKE", "%".$filterArray["Txn_cardinputmethod"]."%");}
                if(isset($filterArray["Txn_cardauthmethod"]) && !empty($filterArray["Txn_cardauthmethod"])){$query->where("Txn_cardauthmethod", "LIKE", "%".$filterArray["Txn_cardauthmethod"]."%");}
                if(isset($filterArray["Txn_cardauthentity"]) && !empty($filterArray["Txn_cardauthentity"])){$query->where("Txn_cardauthentity", "LIKE", "%".$filterArray["Txn_cardauthentity"]."%");}
                if(isset($filterArray["Txn_TVR"]) && !empty($filterArray["Txn_TVR"])){$query->where("Txn_TVR", "LIKE", "%".$filterArray["Txn_TVR"]."%");}
                if(isset($filterArray["MsgSource_value"]) && !empty($filterArray["MsgSource_value"])){$query->where("MsgSource_value", "LIKE", "%".$filterArray["MsgSource_value"]."%");}
                if(isset($filterArray["MsgSource_domesticMaestro"]) && !empty($filterArray["MsgSource_domesticMaestro"])){$query->where("MsgSource_domesticMaestro", "LIKE", "%".$filterArray["MsgSource_domesticMaestro"]."%");}
                if(isset($filterArray["SettlementAmt_value"]) && !empty($filterArray["SettlementAmt_value"])){$query->where("SettlementAmt_value", "LIKE", "%".$filterArray["SettlementAmt_value"]."%");}
                if(isset($filterArray["SettlementAmt_currency"]) && !empty($filterArray["SettlementAmt_currency"])){$query->where("SettlementAmt_currency", "LIKE", "%".$filterArray["SettlementAmt_currency"]."%");}
                if(isset($filterArray["SettlementAmt_rate"]) && !empty($filterArray["SettlementAmt_rate"])){$query->where("SettlementAmt_rate", "LIKE", "%".$filterArray["SettlementAmt_rate"]."%");}
                if(isset($filterArray["SettlementAmt_date"]) && !empty($filterArray["SettlementAmt_date"])){$query->where("SettlementAmt_date", "LIKE", "%".$filterArray["SettlementAmt_date"]."%");}
                if(isset($filterArray["Fee_currency"]) && !empty($filterArray["Fee_currency"])){$query->where("Fee_currency", "LIKE", "%".$filterArray["Fee_currency"]."%");}
                if(isset($filterArray["Classification_RCC"]) && !empty($filterArray["Classification_RCC"])){$query->where("Classification_RCC", "LIKE", "%".$filterArray["Classification_RCC"]."%");}
                if(isset($filterArray["Classification_MCC"]) && !empty($filterArray["Classification_MCC"])){$query->where("Classification_MCC", "LIKE", "%".$filterArray["Classification_MCC"]."%");}
                if(isset($filterArray["OrigTxnAmt_value"]) && !empty($filterArray["OrigTxnAmt_value"])){$query->where("OrigTxnAmt_value", "LIKE", "%".$filterArray["OrigTxnAmt_value"]."%");}
                if(isset($filterArray["OrigTxnAmt_currency"]) && !empty($filterArray["OrigTxnAmt_currency"])){$query->where("OrigTxnAmt_currency", "LIKE", "%".$filterArray["OrigTxnAmt_currency"]."%");}
                if(isset($filterArray["OrigTxnAmt_origItemId"]) && !empty($filterArray["OrigTxnAmt_origItemId"])){$query->where("OrigTxnAmt_origItemId", "LIKE", "%".$filterArray["OrigTxnAmt_origItemId"]."%");}
                if(isset($filterArray["OrigTxnAmt_partial"]) && !empty($filterArray["OrigTxnAmt_partial"])){$query->where("OrigTxnAmt_partial", "LIKE", "%".$filterArray["OrigTxnAmt_partial"]."%");}


            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('cardchrgbackrepres.RecordType', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.ChgbackRepresId', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.LocalDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.SettlementDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Account_no', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.TxnCode_direction', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.TxnCode_Type', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.TxnAmt_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.CashbackAmt_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.BillAmt_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.Fee_direction', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.Fee_direction', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardchrgbackrepres.created_at', 'LIKE',"%{$search}%")

                        ->orWhere('cardchrgbackrepres.file_name', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.ApprCode', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.MerchCode', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Schema', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Repeat', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.ARN', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.FIID', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.RIID', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.ReasonCode', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.PartialReversal', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Card_product', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Card_programid', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Card_branchcode', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Card_productid', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Account_type', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.TxnCode_Group', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.TxnAmt_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.CashbackAmt_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.BillAmt_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.BillAmt_rate', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Trace_auditno', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Trace_origauditno', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Trace_Retrefno', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_code', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_location', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_street', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_city', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_country', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_inputcapability', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Term_authcapability', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_cardholderpresent', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_cardpresent', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_cardinputmethod', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_cardauthmethod', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_cardauthentity', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Txn_TVR', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.MsgSource_value', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.MsgSource_domesticMaestro', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.SettlementAmt_value', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.SettlementAmt_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.SettlementAmt_rate', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.SettlementAmt_date', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Fee_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Classification_RCC', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.Classification_MCC', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.OrigTxnAmt_value', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.OrigTxnAmt_currency', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.OrigTxnAmt_origItemId', 'LIKE',"%{$search}%")
                        ->orWhere('cardchrgbackrepres.OrigTxnAmt_partial', 'LIKE',"%{$search}%")

                        ->orWhere('cardchrgbackrepres.file_date', 'LIKE',"%{$search}%");
                }              
            });
        }
    }
}
