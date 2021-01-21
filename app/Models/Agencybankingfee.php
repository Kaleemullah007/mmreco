<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Agencybankingfee extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'agencybankingfee';

    protected $rules = array(
        'BankingFeeId'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
     	$agencybankingfee = Agencybankingfee::select('agencybankingfee.*');            
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $agencybankingfee = $agencybankingfee->whereBetween('agencybankingfee.file_date' ,[$params['start_date'], $params['end_date']]);

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
            $agencybankingfee = $agencybankingfee->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $agencybankingfee = $agencybankingfee->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['BankingFeeId','AbId','SettlementDate','Desc','Card_PAN','AgencyAccount_no','AgencyAccount_sortcode','AgencyAccount_bankacc','AgencyAccount_name','Amt_direction','Amt_value','created_at','file_date','file_name','File_filedate','File_filename'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'agencybankingfee.created_at';

        $agencybankingfee = $agencybankingfee->orderBy($sort, $order);
        
        $abCount = $agencybankingfee->count();
        // $abCount = count($abCount);

        if($limit != 0){            
            $agencybankingfee = $agencybankingfee->skip($offset)->take($limit)->get();
        }
        else{   
            $agencybankingfee = $agencybankingfee->get();
        }
     
        return array(
            'data' => $agencybankingfee,
            'count' => $abCount
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
                if(isset($filterArray['BankingFeeId']) && !empty($filterArray['BankingFeeId']))
                {
                    $query->where('BankingFeeId', 'LIKE', '%'.$filterArray['BankingFeeId'].'%');
                }

                if(isset($filterArray['AbId']) && !empty($filterArray['AbId']))
                {
                    $query->where('AbId', 'LIKE', '%'.$filterArray['AbId'].'%');
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

                if(isset($filterArray['AgencyAccount_no']) && !empty($filterArray['AgencyAccount_no']))
                {
                    $query->where('AgencyAccount_no', 'LIKE', '%'.$filterArray['AgencyAccount_no'].'%');
                }

                if(isset($filterArray['AgencyAccount_sortcode']) && !empty($filterArray['AgencyAccount_sortcode']))
                {
                    $query->where('AgencyAccount_sortcode', 'LIKE', '%'.$filterArray['AgencyAccount_sortcode'].'%');
                }

                if(isset($filterArray['AgencyAccount_bankacc']) && !empty($filterArray['AgencyAccount_bankacc']))
                {
                    $query->where('AgencyAccount_bankacc', 'LIKE', '%'.$filterArray['AgencyAccount_bankacc'].'%');
                }

                if(isset($filterArray['AgencyAccount_name']) && !empty($filterArray['AgencyAccount_name']))
                {
                    $query->where('AgencyAccount_name', 'LIKE', '%'.$filterArray['AgencyAccount_name'].'%');
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

                if(isset($filterArray['file_name']) && !empty($filterArray['file_name']))
                {
                    $query->where('file_name', 'LIKE', '%'.$filterArray['file_name'].'%');
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
                    $query->where("agencybankingfee.BankingFeeId", 'LIKE', "%$search%")
                        ->orWhere('agencybankingfee.AbId', 'LIKE', "%$search%")
                        ->orWhere('agencybankingfee.SettlementDate', 'LIKE', "%$search%")             
                        ->orWhere('agencybankingfee.Desc', 'LIKE', "%$search%")                   
                        ->orWhere('agencybankingfee.Card_PAN', 'LIKE', "%$search%")                   
                        ->orWhere('agencybankingfee.AgencyAccount_no', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.AgencyAccount_sortcode', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.AgencyAccount_name', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.Amt_direction', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.Amt_value', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.created_at', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.file_date', 'LIKE', "%$search%")                    
                        ->orWhere('agencybankingfee.file_name', 'LIKE', "%$search%")                         
                        ;
                    }    
            });
        }
    }
}
