<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Bankstatement extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'bank_statement';
    public $incrementing = false;
    protected $rules = array(
        'bank_master_id'   => 'required',
        'date'   => 'required',
        'description'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public function agencybankings()
    {
        return $this->belongsToMany('\App\Models\Agencybanking', 'txn_mapping_int','bank_statement_id','txn_table_id')->where(function($query){
                    $query->where(function($query1){
                        $query1->whereIn('txn_mapping_int.txn_type',["Approved","Declined"]);
                        $query1->whereIn('txn_mapping_int.coding',["AB Approved one to one","contra AB Declined/Not Loaded on Card"]);
                        $query1->whereIn('agencybanking.banking_type',["Approved","Declined"]);
                    })->orWhere(function($query1){
                        $query1->where('txn_mapping_int.txn_type','=',"Agencybanking");
                        $query1->where('txn_mapping_int.coding','=',"Manual Compare");
                        $query1->whereIn('agencybanking.banking_type',["Approved","Declined"]);
                    });  
                });
    }


    public function autoCmpagencybankings()
    {
        return $this->belongsToMany('\App\Models\Agencybanking', 'txn_mapping_int','bank_statement_id','txn_table_id')->whereIn('txn_mapping_int.txn_type',["AB Approved","AB Declined","DDR_Bacs","Agencybanking","Approved","Declined"]);
    }


    public function autoCmpfpout()
    {
        return $this->belongsToMany('\App\Models\Fpout', 'txn_mapping_int','bank_statement_id','txn_table_id')->whereIn('txn_mapping_int.txn_type',["FP_Out","Fpout"]);
    }

    public function autoCmpAdv()
    {
        return $this->belongsToMany('\App\Models\Directdebits', 'txn_mapping_int','bank_statement_id','txn_table_id')->whereIn('txn_mapping_int.txn_type',["DD"]);
    }


    public static function getDatatableData($params)
    {
         // $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','agencybanking.SettlementDate')
         //    ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')
         //    ->leftJoin("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")
         //    ->leftJoin("agencybanking", function($join) {

         //        $join->on('agencybanking.id', '=', 'txn_mapping_int.txn_table_id');
         //        $join->where(function($query){
         //            $query->where('txn_mapping_int.txn_type','=',"AB Approved");
         //            $query->where('txn_mapping_int.coding','=',"AB Approved one to one");
         //            $query->where('agencybanking.banking_type','=',"Approved");
         //        })->orWhere(function($query){
         //            $query->where('txn_mapping_int.txn_type','=',"Agencybanking");
         //            $query->where('txn_mapping_int.coding','=',"Manual Compare");
         //            $query->where('agencybanking.banking_type','=',"Approved");
         //        });

                
         //    })->distinct('bank_statement.id')           
         //    ;


         
        $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','bank_statement.id as ids')
            ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')
            ->with('agencybankings');
            

         
         //dd($bankstatement);
        
        if( Input::has('bank_master_id') ){            
            $bankstatement = $bankstatement->where('bank_master_id' ,"=",Input::get('bank_master_id'));    
        }
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $bankstatement = $bankstatement->whereBetween('bank_statement.date' ,[$params['start_date'], $params['end_date']]);

        if( Input::has('minDebit') && Input::has('maxDebit') ){
            $bankstatement = $bankstatement->whereBetween('debit' ,[Input::get('minDebit'),Input::get('maxDebit')]);
        }

        if( Input::has('minCredit') && Input::has('maxCredit') ){
            $bankstatement = $bankstatement->whereBetween('credit' ,[Input::get('minCredit'),Input::get('maxCredit')]);
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
            $bankstatement = $bankstatement->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $bankstatement = $bankstatement->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['id','name','date','description','type','debit','credit','bal','reco_flg','created_at','reco_date','extra_flags'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';

        if(in_array(Input::get('sort'), $allowed_columns))
        {
            $sort = e(Input::get('sort'));
        
            switch ($sort) 
            {
                case 'reco_date':
                   $bankstatement = $bankstatement->OrderSattelmentDate($order);
                    break;
                default:
                    $bankstatement = $bankstatement->orderBy($sort, $order);
                    break;
            }

        }
        else
        {
            $bankstatement = $bankstatement->orderBy("bank_statement.date", "asc");
            $bankstatement = $bankstatement->orderBy("bank_statement.debit", "asc");
            $bankstatement = $bankstatement->orderBy("bank_statement.credit", "asc");
        }
        
        
        $bsCount = $bankstatement->count();
        // $bsCount = count($bsCount);

        if($limit != 0){            
            $bankstatement = $bankstatement->skip($offset)->take($limit)->get();
        }
        else{   
            $bankstatement = $bankstatement->get();
       }
    //    echo "<pre>";
    //    print_r($bankstatement);
    //    die();
        return array(
            'data' => $bankstatement,
            'count' => $bsCount
        );
    }



    public static function getAutoCompareDatatableData($params = null)
    {
        
        $bankstatement = Bankstatement::select('bank_statement.*','bank_master.name','bank_statement.id as ids')
            ->join('bank_master', 'bank_statement.bank_master_id', '=', 'bank_master.id')
            ->where("type","!=","DCA Adjustment")            
            ;

        $bankstatement = $bankstatement->where("bank_statement.reco_flg","N");

        if( !empty($params['bank_master_id']) ){            
            $bankstatement = $bankstatement->where('bank_master_id' ,"=",$params['bank_master_id']);    
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

        $allowed_columns = ['id','name','date','description','type','debit','credit','bal','reco_flg','created_at'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';

        if(in_array(Input::get('sort'), $allowed_columns))
        {
            $sort = e(Input::get('sort'));
            $bankstatement = $bankstatement->orderBy($sort, $order);
        }
        else
        {
            $bankstatement = $bankstatement->orderBy("bank_statement.date", "asc");
            $bankstatement = $bankstatement->orderBy("bank_statement.debit", "asc");
            $bankstatement = $bankstatement->orderBy("bank_statement.credit", "asc");
        }

        // $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'bank_statement.created_at';
        // $bankstatement = $bankstatement->orderBy($sort, $order);
        
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

                if(isset($filterArray['extra_flags']) && !empty($filterArray['extra_flags']))
                {
                    $query->where('extra_flags', 'LIKE', '%'.$filterArray['extra_flags'].'%');
                }

                if(isset($filterArray['reco_date']) && !empty($filterArray['reco_date']))
                {
                    $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                            $query->where("agencybanking.SettlementDate", 'LIKE', '%'.$filterArray['reco_date'].'%');
                        });
                }

                if(isset($filterArray['reco_date']) && !empty($filterArray['reco_date']))
                {
                    $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                            $query->where("agencybanking.SettlementDate", 'LIKE', '%'.$filterArray['reco_date'].'%');
                        });
                }

                if(isset($filterArray['bankingType']) && !empty($filterArray['bankingType']))
                {
                    $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                            $query->where("agencybanking.banking_type", 'LIKE', '%'.$filterArray['bankingType'].'%');
                        });
                }

                if(isset($filterArray['bankingPan']) && !empty($filterArray['bankingPan']))
                {
                    $query->whereHas('agencybankings', function ($query) use ($filterArray) {
                            $query->where("agencybanking.Card_PAN", 'LIKE', '%'.$filterArray['bankingPan'].'%');
                        });
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
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
                        ->orWhere('bank_statement.extra_flags', 'LIKE',"%{$search}%")
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

    public function getBankStatementData($startDate,$endDate)
    {
        $bankstatementData = Bankstatement::select("bank_statement.*" ,"bank_statement.id as bstId")->whereBetween("date",[$startDate,$endDate])->where("reco_flg","N")->orderBy("date","ASC")->get();

        return $bankstatementData;
    }
    public function getBankStatementExactDate($reportDate)
    {
        $bankstatementData = Bankstatement::select("bank_statement.*" ,"bank_statement.id as bstId")->where("date",$reportDate)->where("reco_flg","N")->orderBy("date","ASC")->get();

        return $bankstatementData;
    }
    public function scopeOrderSattelmentDate($query, $order)
    {
        // Left join here, or it will only return results with parents
        return $query->leftJoin("txn_mapping_int","txn_mapping_int.bank_statement_id","=","bank_statement.id")->leftJoin('agencybanking','agencybanking.id', '=', 'txn_mapping_int.txn_table_id')->orderBy('agencybanking.reco_date', $order);
    }

}
