<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Cardbaladjust extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'cardbaladjust';

    protected $rules = array(
        'AdjustId'   => 'required',
        'RecType'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $cardbaladjust = Cardbaladjust::select('cardbaladjust.*','cardbaladjust.id as ids');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardbaladjust = $cardbaladjust->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardbaladjust = $cardbaladjust->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardbaladjust = $cardbaladjust->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['RecType','AdjustId','SettlementDate','Desc','Card_PAN','Account_no','Amount_direction','Amount_value','created_at','file_date','MessageId','LocalDate','VoidedAdjustId','MerchCode','Card_product','Card_programid','Card_branchcode','Card_productid','Account_type','Amount_currency','reco_date','file_name'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardbaladjust.created_at';

        $cardbaladjust = $cardbaladjust->orderBy($sort, $order);
        
        $caCount = $cardbaladjust->count();
        // $caCount = count($caCount);

        if($limit != 0){            
            $cardbaladjust = $cardbaladjust->skip($offset)->take($limit)->get();
        }
        else{   
            $cardbaladjust = $cardbaladjust->get();
        }
     
        return array(
            'data' => $cardbaladjust,
            'count' => $caCount
        );
    }

    public static function getManualDatatableData($params)
    {
        $cardbaladjust = Cardbaladjust::select('cardbaladjust.*','cardbaladjust.id as ids')->where("reco_flg","N");            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $cardbaladjust = $cardbaladjust->whereBetween('file_date' ,[$params['start_date'], $params['end_date']]);

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
            $cardbaladjust = $cardbaladjust->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $cardbaladjust = $cardbaladjust->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['RecType','AdjustId','SettlementDate','Desc','Card_PAN','Account_no','Amount_direction','Amount_value','created_at','file_date' , 'MessageId','LocalDate','VoidedAdjustId','MerchCode','Card_product','Card_programid','Card_branchcode','Card_productid','Account_type','Amount_currency','reco_date','file_name'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'cardbaladjust.created_at';

        $cardbaladjust = $cardbaladjust->orderBy($sort, $order);
        
        $caCount = $cardbaladjust->count();
        // $caCount = count($caCount);

        if($limit != 0){            
            $cardbaladjust = $cardbaladjust->skip($offset)->take($limit)->get();
        }
        else{   
            $cardbaladjust = $cardbaladjust->get();
        }
     
        return array(
            'data' => $cardbaladjust,
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

                if(isset($filterArray['AdjustId']) && !empty($filterArray['AdjustId']))
                {
                    $query->where('AdjustId', 'LIKE', '%'.$filterArray['AdjustId'].'%');
                }

                if(isset($filterArray['SettlementDate']) && !empty($filterArray['SettlementDate']))
                {
                    $query->where('SettlementDate', 'LIKE', '%'.$filterArray['SettlementDate'].'%');
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

                if(isset($filterArray["MessageId"]) && !empty($filterArray["MessageId"])) { $query->where("MessageId", "LIKE", "%".$filterArray["MessageId"]."%"); }
                if(isset($filterArray["LocalDate"]) && !empty($filterArray["LocalDate"])) { $query->where("LocalDate", "LIKE", "%".$filterArray["LocalDate"]."%"); }
                if(isset($filterArray["VoidedAdjustId"]) && !empty($filterArray["VoidedAdjustId"])) { $query->where("VoidedAdjustId", "LIKE", "%".$filterArray["VoidedAdjustId"]."%"); }
                if(isset($filterArray["MerchCode"]) && !empty($filterArray["MerchCode"])) { $query->where("MerchCode", "LIKE", "%".$filterArray["MerchCode"]."%"); }
                if(isset($filterArray["Card_product"]) && !empty($filterArray["Card_product"])) { $query->where("Card_product", "LIKE", "%".$filterArray["Card_product"]."%"); }
                if(isset($filterArray["Card_programid"]) && !empty($filterArray["Card_programid"])) { $query->where("Card_programid", "LIKE", "%".$filterArray["Card_programid"]."%"); }
                if(isset($filterArray["Card_branchcode"]) && !empty($filterArray["Card_branchcode"])) { $query->where("Card_branchcode", "LIKE", "%".$filterArray["Card_branchcode"]."%"); }
                if(isset($filterArray["Card_productid"]) && !empty($filterArray["Card_productid"])) { $query->where("Card_productid", "LIKE", "%".$filterArray["Card_productid"]."%"); }
                if(isset($filterArray["Account_type"]) && !empty($filterArray["Account_type"])) { $query->where("Account_type", "LIKE", "%".$filterArray["Account_type"]."%"); }
                if(isset($filterArray["Amount_currency"]) && !empty($filterArray["Amount_currency"])) { $query->where("Amount_currency", "LIKE", "%".$filterArray["Amount_currency"]."%"); }
                if(isset($filterArray["reco_date"]) && !empty($filterArray["reco_date"])) { $query->where("reco_date", "LIKE", "%".$filterArray["reco_date"]."%"); }
                if(isset($filterArray["file_name"]) && !empty($filterArray["file_name"])) { $query->where("file_name", "LIKE", "%".$filterArray["file_name"]."%"); }


            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('cardbaladjust.RecType', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.AdjustId', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.SettlementDate', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.Desc', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.Card_PAN', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.Account_no', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.Amount_direction', 'LIKE',"%{$search}%")
                        ->orWhere('cardbaladjust.Amount_value', 'LIKE',"%{$search}%")                        
                        ->orWhere('cardbaladjust.created_at', 'LIKE',"%{$search}%")

                        ->orWhere("cardbaladjust.MessageId", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.LocalDate", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.VoidedAdjustId", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.MerchCode", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Card_product", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Card_programid", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Card_branchcode", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Card_productid", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Account_type", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.Amount_currency", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.reco_date", 'LIKE',"%{$search}%")
                        ->orWhere("cardbaladjust.file_name", 'LIKE',"%{$search}%")


                        ->orWhere('cardbaladjust.file_date', 'LIKE',"%{$search}%");
                }                     
            });
        }
    }
}
