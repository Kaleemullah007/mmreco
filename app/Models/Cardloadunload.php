<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardloadunload extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardloadunload';

    protected $rules = array(
        'RecordType'   => 'required',
        'LoadUnloadId'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardloadunload = Cardloadunload::select('cardloadunload.*');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardloadunload = $cardloadunload->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardloadunload = $cardloadunload->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardloadunload = $cardloadunload->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['RecordType','LoadUnloadId','Desc','SettlementDate','Card_PAN','Account_no','Amount_direction','Amount_value','created_at','file_date','file_name','LocalDate','MessageId','MerchCode','LoadSource','LoadType','VoidedLoadUnloadId','Card_productid','Card_product','Card_programid','Card_branchcode','Account_type','Amount_currency'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardloadunload.created_at';

        $cardloadunload = $cardloadunload->orderBy($sort, $order);
        
        $cluCount = $cardloadunload->count();
        // $cluCount = count($cluCount);

        if($limit != 0){            
            $cardloadunload = $cardloadunload->skip($offset)->take($limit)->get();
        }
        else{   
            $cardloadunload = $cardloadunload->get();
        }
     
        return array(
            'data' => $cardloadunload,
            'count' => $cluCount
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

                if(isset($filterArray['LoadUnloadId']) && !empty($filterArray['LoadUnloadId']))
                {
                    $query->where('LoadUnloadId', 'LIKE', '%'.$filterArray['LoadUnloadId'].'%');
                }

                if(isset($filterArray['Desc']) && !empty($filterArray['Desc']))
                {
                    $query->where('Desc', 'LIKE', '%'.$filterArray['Desc'].'%');
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

                if(isset($filterArray['Amount_direction']) && !empty($filterArray['Amount_direction']))
                {
                    $query->where('Amount_direction', 'LIKE', '%'.$filterArray['Amount_direction'].'%');
                }

                if(isset($filterArray['Amount_value']) && !empty($filterArray['Amount_value']))
                {
                    $query->where('Amount_value', 'LIKE', '%'.$filterArray['Amount_value'].'%');
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
                if(isset($filterArray["LocalDate"]) && !empty($filterArray["LocalDate"])){$query->where("LocalDate", "LIKE", "%".$filterArray["LocalDate"]."%");}
                if(isset($filterArray["MessageId"]) && !empty($filterArray["MessageId"])){$query->where("MessageId", "LIKE", "%".$filterArray["MessageId"]."%");}
                if(isset($filterArray["MerchCode"]) && !empty($filterArray["MerchCode"])){$query->where("MerchCode", "LIKE", "%".$filterArray["MerchCode"]."%");}
                if(isset($filterArray["LoadSource"]) && !empty($filterArray["LoadSource"])){$query->where("LoadSource", "LIKE", "%".$filterArray["LoadSource"]."%");}
                if(isset($filterArray["LoadType"]) && !empty($filterArray["LoadType"])){$query->where("LoadType", "LIKE", "%".$filterArray["LoadType"]."%");}
                if(isset($filterArray["VoidedLoadUnloadId"]) && !empty($filterArray["VoidedLoadUnloadId"])){$query->where("VoidedLoadUnloadId", "LIKE", "%".$filterArray["VoidedLoadUnloadId"]."%");}
                if(isset($filterArray["Card_productid"]) && !empty($filterArray["Card_productid"])){$query->where("Card_productid", "LIKE", "%".$filterArray["Card_productid"]."%");}
                if(isset($filterArray["Card_product"]) && !empty($filterArray["Card_product"])){$query->where("Card_product", "LIKE", "%".$filterArray["Card_product"]."%");}
                if(isset($filterArray["Card_programid"]) && !empty($filterArray["Card_programid"])){$query->where("Card_programid", "LIKE", "%".$filterArray["Card_programid"]."%");}
                if(isset($filterArray["Card_branchcode"]) && !empty($filterArray["Card_branchcode"])){$query->where("Card_branchcode", "LIKE", "%".$filterArray["Card_branchcode"]."%");}
                if(isset($filterArray["Account_type"]) && !empty($filterArray["Account_type"])){$query->where("Account_type", "LIKE", "%".$filterArray["Account_type"]."%");}
                if(isset($filterArray["Amount_currency"]) && !empty($filterArray["Amount_currency"])){$query->where("Amount_currency", "LIKE", "%".$filterArray["Amount_currency"]."%");}


            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('cardloadunload.RecordType', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.LoadUnloadId', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Desc', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.SettlementDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Account_no', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Amount_direction', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Amount_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardloadunload.created_at', 'LIKE',"%{$search}%")

                        ->orWhere('cardloadunload.file_name', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.LocalDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.MessageId', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.MerchCode', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.LoadSource', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.LoadType', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.VoidedLoadUnloadId', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Card_productid', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Card_product', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Card_programid', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Card_branchcode', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Account_type', 'LIKE',"%{$search}%")
                        ->orWhere('cardloadunload.Amount_currency', 'LIKE',"%{$search}%")

                        ->orWhere('cardloadunload.file_date', 'LIKE',"%{$search}%");
                }              
            });
        }
    }
}
