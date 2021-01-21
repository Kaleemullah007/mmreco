<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardfee extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardfee';

    protected $rules = array(
        'CardFeeId'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardfee = Cardfee::select('cardfee.*','cardfee.id as ids');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardfee = $cardfee->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardfee = $cardfee->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardfee = $cardfee->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['CardFeeId','SettlementDate','TxId','Desc','Card_PAN','Account_no','TxnCode_direction','TxnCode_ProcCode','FeeClass_interchangeTransaction','FeeAmt_direction','FeeAmt_value','Amt_direction','Amt_value','created_at','file_date','file_name','LoadUnloadId','LocalDate','MerchCode','ReasonCode','FIID','Card_productid','Card_product','Card_programid','Card_branchcode','Account_type','TxnCode_Type','TxnCode_Group','MsgSource_value','MsgSource_domesticMaestro','FeeClass_type','FeeClass_code','FeeAmt_currency','Amt_currency'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardfee.created_at';

        $cardfee = $cardfee->orderBy($sort, $order);
        
        $cfCount = $cardfee->count();
        // $cfCount = count($cfCount);

        if($limit != 0){            
            $cardfee = $cardfee->skip($offset)->take($limit)->get();
        }
        else{   
            $cardfee = $cardfee->get();
        }
     
        return array(
            'data' => $cardfee,
            'count' => $cfCount
        );
    }

    public static function getDatatableDataforLink($params)
    {
        $cardfee = Cardfee::select('cardfee.*','cardfee.id as ids');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardfee = $cardfee->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

        // $cardfee = $cardfee->where(function($query) use($params){
        //     $query->whereNull('daily_balance_shift_id');
        //     $query->orWhere('daily_balance_shift_id',$params['dailybalanceshiftId']);
        // });

        if(!empty($params['dailyBalanceShiftPanNum']))
        {
            $cardfee = $cardfee->where("Card_PAN",$params['dailyBalanceShiftPanNum']);
        }
        
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
            $cardfee = $cardfee->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardfee = $cardfee->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['CardFeeId','SettlementDate','TxId','Desc','Card_PAN','Account_no','TxnCode_direction','TxnCode_ProcCode','FeeClass_interchangeTransaction','FeeAmt_direction','FeeAmt_value','Amt_direction','Amt_value','created_at','file_date','file_name','LoadUnloadId','LocalDate','MerchCode','ReasonCode','FIID','Card_productid','Card_product','Card_programid','Card_branchcode','Account_type','TxnCode_Type','TxnCode_Group','MsgSource_value','MsgSource_domesticMaestro','FeeClass_type','FeeClass_code','FeeAmt_currency','Amt_currency'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardfee.created_at';

        $cardfee = $cardfee->orderBy($sort, $order);
        
        $cfCount = $cardfee->count();
        // $cfCount = count($cfCount);

        if($limit != 0){            
            $cardfee = $cardfee->skip($offset)->take($limit)->get();
        }
        else{   
            $cardfee = $cardfee->get();
        }
     
        return array(
            'data' => $cardfee,
            'count' => $cfCount
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
                if(isset($filterArray['CardFeeId']) && !empty($filterArray['CardFeeId']))
                {
                    $query->where('CardFeeId', 'LIKE', '%'.$filterArray['CardFeeId'].'%');
                }

                if(isset($filterArray['SettlementDate']) && !empty($filterArray['SettlementDate']))
                {
                    $query->where('SettlementDate', 'LIKE', '%'.$filterArray['SettlementDate'].'%');
                }

                if(isset($filterArray['TxId']) && !empty($filterArray['TxId']))
                {
                    $query->where('TxId', 'LIKE', '%'.$filterArray['TxId'].'%');
                }

                if(isset($filterArray['Desc']) && !empty($filterArray['Desc']))
                {
                    $query->where('Desc', 'LIKE', '%'.$filterArray['Desc'].'%');
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

                if(isset($filterArray['TxnCode_ProcCode']) && !empty($filterArray['TxnCode_ProcCode']))
                {
                    $query->where('TxnCode_ProcCode', 'LIKE', '%'.$filterArray['TxnCode_ProcCode'].'%');
                }

                if(isset($filterArray['FeeClass_interchangeTransaction']) && !empty($filterArray['FeeClass_interchangeTransaction']))
                {
                    $query->where('FeeClass_interchangeTransaction', 'LIKE', '%'.$filterArray['FeeClass_interchangeTransaction'].'%');
                }

                if(isset($filterArray['FeeAmt_direction']) && !empty($filterArray['FeeAmt_direction']))
                {
                    $query->where('FeeAmt_direction', 'LIKE', '%'.$filterArray['FeeAmt_direction'].'%');
                }

                if(isset($filterArray['FeeAmt_value']) && !empty($filterArray['FeeAmt_value']))
                {
                    $query->where('FeeAmt_value', 'LIKE', '%'.$filterArray['FeeAmt_value'].'%');
                }

                if(isset($filterArray['Amount_value']) && !empty($filterArray['Amount_value']))
                {
                    $query->where('Amount_value', 'LIKE', '%'.$filterArray['Amount_value'].'%');
                }

                if(isset($filterArray['Amt_direction']) && !empty($filterArray['Amt_direction']))
                {
                    $query->where('Amt_direction', 'LIKE', '%'.$filterArray['Amt_direction'].'%');
                }

                if(isset($filterArray['Amt_value']) && !empty($filterArray['Amt_value']))
                {
                    $query->where('Amt_value', 'LIKE', '%'.$filterArray['Amt_value'].'%');
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
                if(isset($filterArray["LoadUnloadId"]) && !empty($filterArray["LoadUnloadId"])) { $query->where("LoadUnloadId", "LIKE", "%".$filterArray["LoadUnloadId"]."%"); }
                if(isset($filterArray["LocalDate"]) && !empty($filterArray["LocalDate"])) { $query->where("LocalDate", "LIKE", "%".$filterArray["LocalDate"]."%"); }
                if(isset($filterArray["MerchCode"]) && !empty($filterArray["MerchCode"])) { $query->where("MerchCode", "LIKE", "%".$filterArray["MerchCode"]."%"); }
                if(isset($filterArray["ReasonCode"]) && !empty($filterArray["ReasonCode"])) { $query->where("ReasonCode", "LIKE", "%".$filterArray["ReasonCode"]."%"); }
                if(isset($filterArray["FIID"]) && !empty($filterArray["FIID"])) { $query->where("FIID", "LIKE", "%".$filterArray["FIID"]."%"); }
                if(isset($filterArray["Card_productid"]) && !empty($filterArray["Card_productid"])) { $query->where("Card_productid", "LIKE", "%".$filterArray["Card_productid"]."%"); }
                if(isset($filterArray["Card_product"]) && !empty($filterArray["Card_product"])) { $query->where("Card_product", "LIKE", "%".$filterArray["Card_product"]."%"); }
                if(isset($filterArray["Card_programid"]) && !empty($filterArray["Card_programid"])) { $query->where("Card_programid", "LIKE", "%".$filterArray["Card_programid"]."%"); }
                if(isset($filterArray["Card_branchcode"]) && !empty($filterArray["Card_branchcode"])) { $query->where("Card_branchcode", "LIKE", "%".$filterArray["Card_branchcode"]."%"); }
                if(isset($filterArray["Account_type"]) && !empty($filterArray["Account_type"])) { $query->where("Account_type", "LIKE", "%".$filterArray["Account_type"]."%"); }
                if(isset($filterArray["TxnCode_Type"]) && !empty($filterArray["TxnCode_Type"])) { $query->where("TxnCode_Type", "LIKE", "%".$filterArray["TxnCode_Type"]."%"); }
                if(isset($filterArray["TxnCode_Group"]) && !empty($filterArray["TxnCode_Group"])) { $query->where("TxnCode_Group", "LIKE", "%".$filterArray["TxnCode_Group"]."%"); }
                if(isset($filterArray["MsgSource_value"]) && !empty($filterArray["MsgSource_value"])) { $query->where("MsgSource_value", "LIKE", "%".$filterArray["MsgSource_value"]."%"); }
                if(isset($filterArray["MsgSource_domesticMaestro"]) && !empty($filterArray["MsgSource_domesticMaestro"])) { $query->where("MsgSource_domesticMaestro", "LIKE", "%".$filterArray["MsgSource_domesticMaestro"]."%"); }
                if(isset($filterArray["FeeClass_type"]) && !empty($filterArray["FeeClass_type"])) { $query->where("FeeClass_type", "LIKE", "%".$filterArray["FeeClass_type"]."%"); }
                if(isset($filterArray["FeeClass_code"]) && !empty($filterArray["FeeClass_code"])) { $query->where("FeeClass_code", "LIKE", "%".$filterArray["FeeClass_code"]."%"); }
                if(isset($filterArray["FeeAmt_currency"]) && !empty($filterArray["FeeAmt_currency"])) { $query->where("FeeAmt_currency", "LIKE", "%".$filterArray["FeeAmt_currency"]."%"); }
                if(isset($filterArray["Amt_currency"]) && !empty($filterArray["Amt_currency"])) { $query->where("Amt_currency", "LIKE", "%".$filterArray["Amt_currency"]."%"); }


            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('cardfee.CardFeeId', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.SettlementDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.TxId', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.Desc', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.Account_no', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.TxnCode_direction', 'LIKE',"%{$search}%")
                        ->orWhere('cardfee.TxnCode_ProcCode', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.FeeClass_interchangeTransaction', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.FeeAmt_direction', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.FeeAmt_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.Amt_direction', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.Amt_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardfee.created_at', 'LIKE',"%{$search}%")

                        ->orWhere("cardfee.file_name", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.LoadUnloadId", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.LocalDate", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.MerchCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.ReasonCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.FIID", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Card_productid", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Card_product", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Card_programid", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Card_branchcode", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Account_type", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.TxnCode_Type", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.TxnCode_Group", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.MsgSource_value", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.MsgSource_domesticMaestro", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.FeeClass_type", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.FeeClass_code", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.FeeAmt_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardfee.Amt_currency", 'LIKE',"%{$search}%")


                        ->orWhere('cardfee.file_date', 'LIKE',"%{$search}%");
                }                     
            });
        }
    }
}
