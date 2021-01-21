<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Input;
use DB;
use Carbon\Carbon;

class Txnmappingint extends ParexModel
{

   // use SoftDeletes;

    protected $dates = [''];
    protected $table = 'txn_mapping_int';

    protected $rules = array(
        'bank_statement_id'   => 'required',
        'txn_table_id'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getManualComparedData($params)
    {
        $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','bank_statement.id as ids')
            ->join('txn_mapping_int', 'txn_mapping_int.bank_statement_id', '=', 'bank_statement.id')
            ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')
            ->where('txn_mapping_int.coding','Manual Compare')->groupby("bank_statement.id")->with('agencybankings');

        if( Input::has('bank_master_id') ){            
            $bankstatement = $bankstatement->where('bank_master_id' ,"=",Input::get('bank_master_id'));    
        }
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $bankstatement = $bankstatement->whereBetween('bank_statement.date' ,[$params['start_date'], $params['end_date']]);

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
            $bankstatement = $bankstatement->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $bankstatement = $bankstatement->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['id','name','date','description','type','debit','credit','bal','reco_flg','created_at','reco_date'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'bank_statement.created_at';

        switch ($sort) 
        {
            case 'reco_date':
               $bankstatement = $bankstatement->OrderSattelmentDate($order);
                break;
            default:
                $bankstatement = $bankstatement->orderBy($sort, $order);
                break;
        }
        
        
        $bsCount = $bankstatement->count();
        // $bsCount = count($bsCount);

        if($limit != 0){            
            $bankstatement = $bankstatement->skip($offset)->take($limit)->get();
        }
        else{   
            $bankstatement = $bankstatement->get();
       }
        return array(
            'data' => $bankstatement,
            'count' => $bsCount
        );
    }

    public static function getAutoComparedData($params)
    {
      
        $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','bank_statement.id as ids' , 'txn_mapping_int.id as txnIntId','txn_mapping_int.coding')
            ->join('txn_mapping_int', 'txn_mapping_int.bank_statement_id', '=', 'bank_statement.id')
            ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')  
            ->where('reco_flg','Y')    
            ->groupBy("bank_statement.id")->with('agencybankings');   
            ;
          
        if( Input::has('bank_master_id') ){            
            $bankstatement = $bankstatement->where('bank_master_id' ,"=",Input::get('bank_master_id'));    
        }
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $bankstatement = $bankstatement->whereBetween('bank_statement.date' ,[$params['start_date'], $params['end_date']]);

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
            $bankstatement = $bankstatement->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $bankstatement = $bankstatement->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['id','name','date','description','type','debit','credit','bal','reco_flg','created_at','reco_date'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'bank_statement.created_at';

        switch ($sort) 
        {
            case 'reco_date':
               $bankstatement = $bankstatement->OrderSattelmentDate($order);
                break;
            default:
                $bankstatement = $bankstatement->orderBy($sort, $order);
                break;
        }
            
       $bsCount =$bankstatement->toBase()->getCountForPagination();

    //  $bsCount =$bankstatement->count();
   
        if($limit != 0){            
            $bankstatement = $bankstatement->skip($offset)->take($limit)->get();
        }
        else{   
            $bankstatement = $bankstatement->get();
       }
     
        return array(
            'data' => $bankstatement,
            'count' => $bsCount
        );
    }

    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['name']) && !empty($filterArray['name']))
                {
                    $query->where('name', 'LIKE', '%'.$filterArray['name'].'%');
                }

                if(isset($filterArray['date']) && !empty($filterArray['date']))
                {
                    $query->where('date', 'LIKE', '%'.$filterArray['date'].'%');
                }

                if(isset($filterArray['description']) && !empty($filterArray['description']))
                {
                    $query->where('description', 'LIKE', '%'.$filterArray['description'].'%');
                }

                if(isset($filterArray['type']) && !empty($filterArray['type']))
                {
                    $query->where('type', 'LIKE', '%'.$filterArray['type'].'%');
                }

                if(isset($filterArray['debit']) && !empty($filterArray['debit']))
                {
                    $query->where('debit', 'LIKE', '%'.$filterArray['debit'].'%');
                }

                if(isset($filterArray['credit']) && !empty($filterArray['credit']))
                {
                    $query->where('credit', 'LIKE', '%'.$filterArray['credit'].'%');
                }

                if(isset($filterArray['bal']) && !empty($filterArray['bal']))
                {
                    $query->where('bal', 'LIKE', '%'.$filterArray['bal'].'%');
                }

                if(isset($filterArray['reco_flg']) && !empty($filterArray['reco_flg']))
                {
                    $query->where('reco_flg', 'LIKE', '%'.$filterArray['reco_flg'].'%');
                }

                if(isset($filterArray['txncmptype']) && !empty($filterArray['txncmptype']))
                {
                    if(strtolower($filterArray['txncmptype']) == "manual")
                    {
                        $query->where('txn_mapping_int.coding', '=', 'Manual Compare');
                    }
                    else
                    {
                        $query->where('txn_mapping_int.coding', '!=', 'Manual Compare');
                    }
                }

                if(isset($filterArray['reco_date']) && !empty($filterArray['reco_date']))
                {
                    $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                            $query->where("agencybanking.SettlementDate", 'LIKE', '%'.$filterArray['reco_date'].'%');
                        });
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
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
                    $query->where('bank_master.name', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.date', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.description', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.type', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.debit', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.credit', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.bal', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.reco_flg', 'LIKE',"%{$search}%")
                        ->orWhere('bank_statement.created_at', 'LIKE',"%{$search}%")
                        ->orWhere(function ($query) use ($search) {
                            $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                                $query->where("agencybanking.SettlementDate", 'LIKE', '%'.$search.'%');
                            });
                        });
                    }                    
            });
        }
    }

    public static function fetchAllTxnByBstId($bstId)
    {
        $bstIntData = Txnmappingint::select("txn_mapping_int.*","txn_mapping_int.id as ids")->where("txn_mapping_int.bank_statement_id",$bstId)->get();

        return $bstIntData;
    }

}
