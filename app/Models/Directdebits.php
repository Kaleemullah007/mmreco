<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Input;

class Directdebits extends ParexModel
{

   use SoftDeletes;

    protected $dates = [''];
    protected $table = 'direct_debits';

    protected $rules = array(
        // 'Processing_Date'   => 'required',
        'Due_Date'   => 'required',
        'amount'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $directdebits = Directdebits::select('direct_debits.id','direct_debits.Processing_Date','direct_debits.Due_Date','direct_debits.SUN','direct_debits.Sun_Name','direct_debits.Trans_Code','direct_debits.DReference','direct_debits.diban','direct_debits.status','direct_debits.amount','direct_debits.Token_Number');

        if(isset($params['start_date']) && isset($params['end_date']))
        {            
            $directdebits = $directdebits->whereBetween('direct_debits.Due_Date' ,[$params['start_date'], $params['end_date']]);
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

        if (Input::get('sort')=='Processing_Date') {
            $sort = 'Processing_Date';
        } else {
            $sort = e(Input::get('sort'));
        }

        // For Datatable Search & Filter
        if (Input::has('filter')) 
        {
            $directdebits = $directdebits->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $directdebits = $directdebits->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['Processing_Date','Due_Date','SUN','Sun_Name','Trans_Code', 'DReference','diban', 'status','amount','Token_Number','deleted_at'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'direct_debits.created_at';

        $directdebits = $directdebits->orderBy($sort, $order);
                
        $ddCount = $directdebits->count();
        // $ddCount = count($ddCount);

        if($limit != 0)
            $directdebits = $directdebits->skip($offset)->take($limit)->get();
        else
            $directdebits = $directdebits->get();

        return array(
            'data' => $directdebits,
            'count' => $ddCount
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
                if(isset($filterArray['Processing_Date']) && !empty($filterArray['Processing_Date']))
                {
                    $query->where('Processing_Date', 'LIKE', '%'.$filterArray['Processing_Date'].'%');
                }

                if(isset($filterArray['Due_Date']) && !empty($filterArray['Due_Date']))
                {
                    $query->where('Due_Date', 'LIKE', '%'.$filterArray['Due_Date'].'%');
                }

                if(isset($filterArray['SUN']) && !empty($filterArray['SUN']))
                {
                    $query->where('SUN', 'LIKE', '%'.$filterArray['SUN'].'%');
                }

                if(isset($filterArray['Sun_Name']) && !empty($filterArray['Sun_Name']))
                {
                    $query->where('Sun_Name', 'LIKE', '%'.$filterArray['Sun_Name'].'%');
                }

                if(isset($filterArray['Trans_Code']) && !empty($filterArray['Trans_Code']))
                {
                    $query->where('Trans_Code', 'LIKE', '%'.$filterArray['Trans_Code'].'%');
                }

                if(isset($filterArray['DReference']) && !empty($filterArray['DReference']))
                {
                    $query->where('DReference', 'LIKE', '%'.$filterArray['DReference'].'%');
                }

                if(isset($filterArray['diban']) && !empty($filterArray['diban']))
                {
                    $query->where('diban', 'LIKE', '%'.$filterArray['diban'].'%');
                }

                if(isset($filterArray['status']) && !empty($filterArray['status']))
                {
                    $query->where('status', 'LIKE', '%'.$filterArray['status'].'%');
                }

                if(isset($filterArray['amount']) && !empty($filterArray['amount']))
                {
                    $query->where('amount', 'LIKE', '%'.$filterArray['amount'].'%');
                }
                if(isset($filterArray['Token_Number']) && !empty($filterArray['Token_Number']))
                {
                    $query->where('Token_Number', 'LIKE', '%'.$filterArray['Token_Number'].'%');
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
                $query->where(DB::raw("direct_debits.Processing_Date"), 'LIKE', "%$search%")
                    ->orWhere('direct_debits.Due_Date', 'LIKE', "%$search%")
                    ->orWhere('direct_debits.SUN', 'LIKE', "%$search%")             
                    ->orWhere('direct_debits.Sun_Name', 'LIKE', "%$search%")                   
                    ->orWhere('direct_debits.Trans_Code', 'LIKE', "%$search%")
                    ->orWhere('direct_debits.DReference', 'LIKE', "%$search%")                                       
                    ->orWhere('direct_debits.diban', 'LIKE', "%$search%")
                    ->orWhere('direct_debits.status', 'LIKE', "%$search%")                
                    ->orWhere('direct_debits.amount', 'LIKE', "%$search%")                
                    ->orWhere('direct_debits.Token_Number', 'LIKE', "%$search%")                
                    ;
                }
            });
        }
    }

    public function fetchDDDataForAdvice($obj)
    {
        $dayName = date("D",strtotime($obj->file_date));
        if($dayName == 'Fri')
        {
             $startDate = date("Y-m-d",strtotime("+3 day",strtotime($obj->file_date)));
        }
        else
        {
            $startDate = date("Y-m-d",strtotime("+1 day",strtotime($obj->file_date)));
        }

        $searchData = $obj->ab_sort_code.$obj->ab_account_number;
       

        $agencybankingData = Directdebits::select("direct_debits.*","direct_debits.id as ids")
                            ->where("direct_debits.Due_Date",$startDate)
                            ->where('direct_debits.diban','LIKE','%'.$searchData.'%')
                            ->where('reco_flg','N')
                            ->orderBy("direct_debits.Due_Date","DESC")
                            ->get();

        $totalAmt = 0;
        if(!empty($agencybankingData))
        {
            foreach ($agencybankingData as $key => $value) {
                 $totalAmt = $totalAmt + $value->amount;
            }
           
        }

        if($totalAmt == $obj->actual_amount)
        {
            return $agencybankingData;
        }
        else
        {
            return array();
        }

    }
}
