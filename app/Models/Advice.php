<?php
namespace App\Models;

use App\Models\ParexModel;
use App\Models\Agencybanking;
use App\Models\Directdebits;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Advice extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'advice';

    protected $rules = array(
       
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public function getAdviceAbData($bankStmt)
    {
        $dayName = date("D",strtotime($bankStmt->date));

        if($dayName == 'Mon')
            $file_date = date("Y-m-d",strtotime("-3 day",strtotime($bankStmt->date)));
        else
            $file_date = date("Y-m-d",strtotime("-1 day",strtotime($bankStmt->date)));
        
        $advData = Agencybanking::select("agencybanking.*","agencybanking.id as ids","advice.id as advice_id",DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS int_type"))->join("advice","advice.related_table_id","=","agencybanking.id")->whereIn("advice.type",["abapproved","abdeclined"])->where("advice.file_date",$file_date)->where("agencybanking.SettlementDate",$bankStmt->date)->get();
        return $advData;
    }

    public function getDDAbData($bankStmt)
    {
        $dayName = date("D",strtotime($bankStmt->date));

        if($dayName == 'Mon')
            $file_date = date("Y-m-d",strtotime("-3 day",strtotime($bankStmt->date)));
        else
            $file_date = date("Y-m-d",strtotime("-1 day",strtotime($bankStmt->date)));
        
        $advData = Directdebits::select("direct_debits.*","direct_debits.id as ids","advice.id as advice_id",DB::raw(" 'DD' as int_type"))->join("advice",function($join){
             $join->whereRaw("find_in_set(direct_debits.id, advice.related_table_id)");
         })->whereIn("advice.type",["dd"])->where("advice.file_date",$file_date)->where("direct_debits.Due_Date",$bankStmt->date)->get();

        return $advData;
    }

    public function getAdviceDataByDate($startDate , $endDate)
    {
        $fpoutData = Advice::whereBetween("file_date",[$startDate,$endDate])->whereNull("related_table_id")->where("reco_flg","N")->get();

        return $fpoutData;
    }

    public static function getDatatableData($params)
    {
        $cardbaladjust = Advice::select('advice.*','advice.id as ids');            
        
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

        $allowed_columns = ['ab_sort_code','ab_account_number','code','ext_bank_sort_code','ext_bank_acc_number','amount_in_cent','actual_amount','ext_name','created_at','file_date','C','A','ref','ab_name','advice_number','X','Y','Z'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'advice.created_at';

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

     public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }

                if(isset($filterArray['ab_sort_code']) && !empty($filterArray['ab_sort_code']))
                {
                    $query->where('ab_sort_code', 'LIKE', '%'.$filterArray['ab_sort_code'].'%');
                }

                if(isset($filterArray['ab_account_number']) && !empty($filterArray['ab_account_number']))
                {
                    $query->where('ab_account_number', 'LIKE', '%'.$filterArray['ab_account_number'].'%');
                }

                if(isset($filterArray['code']) && !empty($filterArray['code']))
                {
                    $query->where('code', 'LIKE', '%'.$filterArray['code'].'%');
                }

                if(isset($filterArray['ext_bank_sort_code']) && !empty($filterArray['ext_bank_sort_code']))
                {
                    $query->where('ext_bank_sort_code', 'LIKE', '%'.$filterArray['ext_bank_sort_code'].'%');
                }

                if(isset($filterArray['ext_bank_acc_number']) && !empty($filterArray['ext_bank_acc_number']))
                {
                    $query->where('ext_bank_acc_number', 'LIKE', '%'.$filterArray['ext_bank_acc_number'].'%');
                }

                if(isset($filterArray['amount_in_cent']) && !empty($filterArray['amount_in_cent']))
                {
                    $query->where('amount_in_cent', 'LIKE', '%'.$filterArray['amount_in_cent'].'%');
                }

                if(isset($filterArray['actual_amount']) && !empty($filterArray['actual_amount']))
                {
                    $query->where('actual_amount', 'LIKE', '%'.$filterArray['actual_amount'].'%');
                }

                if(isset($filterArray['ext_name']) && !empty($filterArray['ext_name']))
                {
                    $query->where('ext_name', 'LIKE', '%'.$filterArray['ext_name'].'%');
                }

                if(isset($filterArray['C']) && !empty($filterArray['C']))
                {
                    $query->where('C', 'LIKE', '%'.$filterArray['C'].'%');
                }

                if(isset($filterArray['A']) && !empty($filterArray['A']))
                {
                    $query->where('A', 'LIKE', '%'.$filterArray['A'].'%');
                }

                if(isset($filterArray['ref']) && !empty($filterArray['ref']))
                {
                    $query->where('ref', 'LIKE', '%'.$filterArray['ref'].'%');
                }

                if(isset($filterArray['ab_name']) && !empty($filterArray['ab_name']))
                {
                    $query->where('ab_name', 'LIKE', '%'.$filterArray['ab_name'].'%');
                }

                if(isset($filterArray['advice_number']) && !empty($filterArray['advice_number']))
                {
                    $query->where('advice_number', 'LIKE', '%'.$filterArray['advice_number'].'%');
                }

                if(isset($filterArray['X']) && !empty($filterArray['X']))
                {
                    $query->where('X', 'LIKE', '%'.$filterArray['X'].'%');
                }

                if(isset($filterArray['Y']) && !empty($filterArray['Y']))
                {
                    $query->where('Y', 'LIKE', '%'.$filterArray['Y'].'%');
                }

                if(isset($filterArray['Z']) && !empty($filterArray['Z']))
                {
                    $query->where('Z', 'LIKE', '%'.$filterArray['Z'].'%');
                }

            });
        }
        else
        {
            $search = explode('+', $search);
            return $query->where(function ($query) use ($search) 
            {
                foreach ($search as $search) 
                {                            
                    $query->where('advice.file_date', 'LIKE',"%{$search}%")
                        ->orWhere('advice.ab_sort_code', 'LIKE',"%{$search}%")
                        ->orWhere('advice.ab_account_number', 'LIKE',"%{$search}%")
                        ->orWhere('advice.code', 'LIKE',"%{$search}%")
                        ->orWhere('advice.ext_bank_sort_code', 'LIKE',"%{$search}%")
                        ->orWhere('advice.ext_bank_acc_number', 'LIKE',"%{$search}%")
                        ->orWhere('advice.amount_in_cent', 'LIKE',"%{$search}%")
                        ->orWhere('advice.actual_amount', 'LIKE',"%{$search}%") 

                        ->orWhere('advice.C', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.A', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.ref', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.ab_name', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.advice_number', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.X', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.Y', 'LIKE',"%{$search}%")  
                        ->orWhere('advice.Z', 'LIKE',"%{$search}%")  
                                              
                        ->orWhere('advice.ext_name', 'LIKE',"%{$search}%");
                }                     
            });
        }
    }
}
