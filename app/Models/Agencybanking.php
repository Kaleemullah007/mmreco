<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Agencybanking extends ParexModel
{

   use SoftDeletes;

    protected $dates = [''];
    protected $table = 'agencybanking';
    public $incrementing = false;
    protected $rules = array(
        'banking_type'   => 'required',
        'CashType'   => 'required',
        'BankingId'   => 'required',
        'SettlementDate'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public function bankstatements()
    {
        return $this->belongsToMany('\App\Models\Bankstatement', 'txn_mapping_int','bank_statement_id','txn_table_id')->where(function($query){
                    $query->where(function($query1){
                        $query1->whereIn('txn_mapping_int.txn_type',["AB Approved","AB Declined"]);
                        $query1->whereIn('txn_mapping_int.coding',["AB Approved one to one","contra AB Declined/Not Loaded on Card"]);
                        $query1->whereIn('agencybanking.banking_type',["Approved","Declined"]);
                    })->orWhere(function($query1){
                        $query1->where('txn_mapping_int.txn_type','=',"Agencybanking");
                        $query1->where('txn_mapping_int.coding','=',"Manual Compare");
                        $query1->whereIn('agencybanking.banking_type',["Approved","Declined"]);
                    });  
                });
    }

    public function fpoutrel()
    {
        return $this->hasMany('\App\Models\Fpout','agencybanking_Id');
    }

    public static function getDatatableData($params)
    {
        
        $agencybanking = Agencybanking::select('agencybanking.*')
            ->where('banking_type','=',$params['banking_type'])
            ->with('fpoutrel')
            ;

        if(isset($params['start_date']) && isset($params['end_date'])){            
            $agencybanking = $agencybanking->whereBetween('agencybanking.file_date' ,[$params['start_date'], $params['end_date']]);
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
            $agencybanking = $agencybanking->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $agencybanking = $agencybanking->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['CashType','BankingId','SettlementDate','Desc','DeclineReason','Card_PAN','AgencyAccount_no','AgencyAccount_sortcode','External_sortcode','External_bankacc','External_name','CashAmt_value','Fee_direction','reco_date','Card_productid','Card_product','Card_programid','Card_branchcode','AgencyAccount_type','AgencyAccount_bankacc','AgencyAccount_name','CashCode_direction','CashCode_CashType','CashCode_CashGroup','CashAmt_currency','Fee_value','Fee_currency','BillAmt_value','BillAmt_currency','BillAmt_rate','OrigTxnAmt_value','OrigTxnAmt_currency','OrigTxnAmt_partial','OrigTxnAmt_origItemId','File_filename','File_filedate','file_date','file_name'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'agencybanking.created_at';

        $agencybanking = $agencybanking->orderBy($sort, $order);
        
        $abCount = $agencybanking->count();
        // $abCount = count($abCount);

        if($limit != 0){            
            $agencybanking = $agencybanking->skip($offset)->take($limit)->get();
        }
        else{   
            $agencybanking = $agencybanking->get();
        }
     
        return array(
            'data' => $agencybanking,
            'count' => $abCount
        );
    }

    public static function getAbdDatatableData($params)
    {
        
        $agencybanking = Agencybanking::select('agencybanking.*','agencybanking.id as ids')
            ->where('banking_type','=',"Approved")->where('agencybanking.reco_flg',"N");
            ;

        if(isset($params['start_date']) && isset($params['end_date'])){            
            $agencybanking = $agencybanking->whereBetween('agencybanking.file_date' ,[$params['start_date'], $params['end_date']]);
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
            $agencybanking = $agencybanking->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $agencybanking = $agencybanking->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['CashType','BankingId','SettlementDate','Desc','DeclineReason','Card_PAN','AgencyAccount_no','AgencyAccount_sortcode','External_sortcode','External_bankacc','External_name','CashAmt_value','Fee_direction','Card_productid','Card_product','Card_programid','Card_branchcode','AgencyAccount_type','AgencyAccount_bankacc','AgencyAccount_name','CashCode_direction','CashCode_CashType','CashCode_CashGroup','CashAmt_currency','Fee_value','Fee_currency','BillAmt_value','BillAmt_currency','BillAmt_rate','OrigTxnAmt_value','OrigTxnAmt_currency','OrigTxnAmt_partial','OrigTxnAmt_origItemId','File_filename','File_filedate','file_date','file_name'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';

        if(in_array(Input::get('sort'), $allowed_columns))
        {
            $sort = e(Input::get('sort'));
            $agencybanking = $agencybanking->orderBy($sort, $order);
        }
        else
        {
            $agencybanking = $agencybanking->orderBy("agencybanking.SettlementDate", "asc");
            $agencybanking = $agencybanking->orderBy("agencybanking.CashAmt_value", "asc");
            $agencybanking = $agencybanking->orderBy("agencybanking.File_filedate", "asc");
        }

        // $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'agencybanking.created_at';
        // $agencybanking = $agencybanking->orderBy($sort, $order);
        
        $abCount = $agencybanking->count();
        //$abCount = count($abCount);

        if($limit != 0){            
            $agencybanking = $agencybanking->skip($offset)->take($limit)->get();
        }
        else{   
            $agencybanking = $agencybanking->get();
        }
     
        return array(
            'data' => $agencybanking,
            'count' => $abCount
        );
    }

    public static function getAbDeclinedDatatable($params)
    {
        
        $agencybanking = Agencybanking::select('agencybanking.*','agencybanking.id as ids')
            ->where('banking_type','=',"Declined")->where('agencybanking.reco_flg',"N");
            ;

        if(isset($params['start_date']) && isset($params['end_date'])){            
            $agencybanking = $agencybanking->whereBetween('agencybanking.file_date' ,[$params['start_date'], $params['end_date']]);
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
            $agencybanking = $agencybanking->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $agencybanking = $agencybanking->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['CashType','BankingId','SettlementDate','Desc','DeclineReason','Card_PAN','AgencyAccount_no','AgencyAccount_sortcode','External_sortcode','External_bankacc','External_name','CashAmt_value','Fee_direction','Card_productid','Card_product','Card_programid','Card_branchcode','AgencyAccount_type','AgencyAccount_bankacc','AgencyAccount_name','CashCode_direction','CashCode_CashType','CashCode_CashGroup','CashAmt_currency','Fee_value','Fee_currency','BillAmt_value','BillAmt_currency','BillAmt_rate','OrigTxnAmt_value','OrigTxnAmt_currency','OrigTxnAmt_partial','OrigTxnAmt_origItemId','File_filename','File_filedate','file_date','file_name'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';

        if(in_array(Input::get('sort'), $allowed_columns))
        {
            $sort = e(Input::get('sort'));
            $agencybanking = $agencybanking->orderBy($sort, $order);
        }
        else
        {
            $agencybanking = $agencybanking->orderBy("agencybanking.SettlementDate", "asc");
            $agencybanking = $agencybanking->orderBy("agencybanking.CashAmt_value", "asc");
            $agencybanking = $agencybanking->orderBy("agencybanking.File_filedate", "asc");
        }

        // $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'agencybanking.created_at';
        // $agencybanking = $agencybanking->orderBy($sort, $order);
        
        $abCount = $agencybanking->count();
        // $abCount = count($abCount);

        if($limit != 0){            
            $agencybanking = $agencybanking->skip($offset)->take($limit)->get();
        }
        else{   
            $agencybanking = $agencybanking->get();
        }
     
        return array(
            'data' => $agencybanking,
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
                if(isset($filterArray['CashType']) && !empty($filterArray['CashType']))
                {
                    $query->where('CashType', 'LIKE', '%'.$filterArray['CashType'].'%');
                }

                if(isset($filterArray['BankingId']) && !empty($filterArray['BankingId']))
                {
                    $query->where('BankingId', 'LIKE', '%'.$filterArray['BankingId'].'%');
                }

                if(isset($filterArray['SettlementDate']) && !empty($filterArray['SettlementDate']))
                {
                    $query->where('SettlementDate', 'LIKE', '%'.$filterArray['SettlementDate'].'%');
                }

                if(isset($filterArray['Desc']) && !empty($filterArray['Desc']))
                {
                    $query->where('Desc', 'LIKE', '%'.$filterArray['Desc'].'%');
                }

                if(isset($filterArray['DeclineReason']) && !empty($filterArray['DeclineReason']))
                {
                    $query->where('DeclineReason', 'LIKE', '%'.$filterArray['DeclineReason'].'%');
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

                if(isset($filterArray['External_sortcode']) && !empty($filterArray['External_sortcode']))
                {
                    $query->where('External_sortcode', 'LIKE', '%'.$filterArray['External_sortcode'].'%');
                }

                if(isset($filterArray['External_bankacc']) && !empty($filterArray['External_bankacc']))
                {
                    $query->where('External_bankacc', 'LIKE', '%'.$filterArray['External_bankacc'].'%');
                }

                if(isset($filterArray['External_name']) && !empty($filterArray['External_name']))
                {
                    $query->where('External_name', 'LIKE', '%'.$filterArray['External_name'].'%');
                }

                if(isset($filterArray['CashAmt_value']) && !empty($filterArray['CashAmt_value']))
                {
                    $query->where('CashAmt_value', 'LIKE', '%'.$filterArray['CashAmt_value'].'%');
                }

                if(isset($filterArray['Fee_direction']) && !empty($filterArray['Fee_direction']))
                {
                    $query->where('Fee_direction', 'LIKE', '%'.$filterArray['Fee_direction'].'%');
                }

                if(isset($filterArray['reco_date']) && !empty($filterArray['reco_date']))
                {
                    $query->where('reco_date', 'LIKE', '%'.$filterArray['reco_date'].'%');
                }


                if(isset($filterArray['Card_productid']) && !empty($filterArray['Card_productid']))
                {
                    $query->where('Card_productid', 'LIKE', '%'.$filterArray['Card_productid'].'%');
                }

                if(isset($filterArray['Card_product']) && !empty($filterArray['Card_product']))
                {
                    $query->where('Card_product', 'LIKE', '%'.$filterArray['Card_product'].'%');
                }

                if(isset($filterArray['Card_programid']) && !empty($filterArray['Card_programid']))
                {
                    $query->where('Card_programid', 'LIKE', '%'.$filterArray['Card_programid'].'%');
                }

                if(isset($filterArray['Card_branchcode']) && !empty($filterArray['Card_branchcode']))
                {
                    $query->where('Card_branchcode', 'LIKE', '%'.$filterArray['Card_branchcode'].'%');
                }

                if(isset($filterArray['AgencyAccount_type']) && !empty($filterArray['AgencyAccount_type']))
                {
                    $query->where('AgencyAccount_type', 'LIKE', '%'.$filterArray['AgencyAccount_type'].'%');
                }

                if(isset($filterArray['AgencyAccount_bankacc']) && !empty($filterArray['AgencyAccount_bankacc']))
                {
                    $query->where('AgencyAccount_bankacc', 'LIKE', '%'.$filterArray['AgencyAccount_bankacc'].'%');
                }

                if(isset($filterArray['AgencyAccount_name']) && !empty($filterArray['AgencyAccount_name']))
                {
                    $query->where('AgencyAccount_name', 'LIKE', '%'.$filterArray['AgencyAccount_name'].'%');
                }

                if(isset($filterArray['CashCode_direction']) && !empty($filterArray['CashCode_direction']))
                {
                    $query->where('CashCode_direction', 'LIKE', '%'.$filterArray['CashCode_direction'].'%');
                }

                if(isset($filterArray['CashCode_CashType']) && !empty($filterArray['CashCode_CashType']))
                {
                    $query->where('CashCode_CashType', 'LIKE', '%'.$filterArray['CashCode_CashType'].'%');
                }

                if(isset($filterArray['CashCode_CashGroup']) && !empty($filterArray['CashCode_CashGroup']))
                {
                    $query->where('CashCode_CashGroup', 'LIKE', '%'.$filterArray['CashCode_CashGroup'].'%');
                }

                if(isset($filterArray['CashAmt_currency']) && !empty($filterArray['CashAmt_currency']))
                {
                    $query->where('CashAmt_currency', 'LIKE', '%'.$filterArray['CashAmt_currency'].'%');
                }

                if(isset($filterArray['Fee_value']) && !empty($filterArray['Fee_value']))
                {
                    $query->where('Fee_value', 'LIKE', '%'.$filterArray['Fee_value'].'%');
                }

                if(isset($filterArray['Fee_currency']) && !empty($filterArray['Fee_currency']))
                {
                    $query->where('Fee_currency', 'LIKE', '%'.$filterArray['Fee_currency'].'%');
                }

                if(isset($filterArray['BillAmt_value']) && !empty($filterArray['BillAmt_value']))
                {
                    $query->where('BillAmt_value', 'LIKE', '%'.$filterArray['BillAmt_value'].'%');
                }

                if(isset($filterArray['BillAmt_currency']) && !empty($filterArray['BillAmt_currency']))
                {
                    $query->where('BillAmt_currency', 'LIKE', '%'.$filterArray['BillAmt_currency'].'%');
                }

                if(isset($filterArray['BillAmt_rate']) && !empty($filterArray['BillAmt_rate']))
                {
                    $query->where('BillAmt_rate', 'LIKE', '%'.$filterArray['BillAmt_rate'].'%');
                }

                if(isset($filterArray['OrigTxnAmt_value']) && !empty($filterArray['OrigTxnAmt_value']))
                {
                    $query->where('OrigTxnAmt_value', 'LIKE', '%'.$filterArray['OrigTxnAmt_value'].'%');
                }

                if(isset($filterArray['OrigTxnAmt_currency']) && !empty($filterArray['OrigTxnAmt_currency']))
                {
                    $query->where('OrigTxnAmt_currency', 'LIKE', '%'.$filterArray['OrigTxnAmt_currency'].'%');
                }

                if(isset($filterArray['OrigTxnAmt_partial']) && !empty($filterArray['OrigTxnAmt_partial']))
                {
                    $query->where('OrigTxnAmt_partial', 'LIKE', '%'.$filterArray['OrigTxnAmt_partial'].'%');
                }

                if(isset($filterArray['OrigTxnAmt_origItemId']) && !empty($filterArray['OrigTxnAmt_origItemId']))
                {
                    $query->where('OrigTxnAmt_origItemId', 'LIKE', '%'.$filterArray['OrigTxnAmt_origItemId'].'%');
                }

                if(isset($filterArray['DeclineReason']) && !empty($filterArray['DeclineReason']))
                {
                    $query->where('DeclineReason', 'LIKE', '%'.$filterArray['DeclineReason'].'%');
                }

                if(isset($filterArray['File_filedate']) && !empty($filterArray['File_filedate']))
                {
                    $query->where('File_filedate', 'LIKE', '%'.$filterArray['File_filedate'].'%');
                }

                if(isset($filterArray['File_filename']) && !empty($filterArray['File_filename']))
                {
                    $query->where('File_filename', 'LIKE', '%'.$filterArray['File_filename'].'%');
                }

                if(isset($filterArray['file_date']) && !empty($filterArray['file_date']))
                {
                    $query->where('file_date', 'LIKE', '%'.$filterArray['file_date'].'%');
                }

                if(isset($filterArray['file_name']) && !empty($filterArray['file_name']))
                {
                    $query->where('file_name', 'LIKE', '%'.$filterArray['file_name'].'%');
                }

                if(isset($filterArray['fpoutRecoDate']) && !empty($filterArray['fpoutRecoDate']))
                {
                    $query->whereHas('fpoutrel', function ($query) use ($filterArray) {
                            $query->where("fp_out.reco_date", 'LIKE', '%'.$filterArray['fpoutRecoDate'].'%');
                        });
                }
                
                if(isset($filterArray['OutwardAcceptedValue']) && !empty($filterArray['OutwardAcceptedValue']))
                {
                    $query->whereHas('fpoutrel', function ($query) use ($filterArray) {
                            $query->where("fp_out.OutwardAcceptedValue", 'LIKE', '%'.$filterArray['OutwardAcceptedValue'].'%');
                        });
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
                    $query->where(DB::raw("agencybanking.CashType"), 'LIKE', "%$search%")
                        ->orWhere('agencybanking.BankingId', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.SettlementDate', 'LIKE', "%$search%")             
                        ->orWhere('agencybanking.Desc', 'LIKE', "%$search%")                   
                        ->orWhere('agencybanking.DeclineReason', 'LIKE', "%$search%")                   
                        ->orWhere('agencybanking.Card_PAN', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.AgencyAccount_no', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.AgencyAccount_sortcode', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.External_sortcode', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.External_bankacc', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.External_name', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.CashAmt_value', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.reco_date', 'LIKE', "%$search%")                    
                        ->orWhere('agencybanking.Fee_direction', 'LIKE', "%$search%") 
                        ->orWhere('agencybanking.Card_productid', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.Card_product', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.Card_programid', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.Card_branchcode', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.AgencyAccount_type', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.AgencyAccount_bankacc', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.AgencyAccount_name', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.CashCode_direction', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.CashCode_CashType', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.CashCode_CashGroup', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.CashAmt_currency', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.Fee_value', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.Fee_currency', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.BillAmt_value', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.BillAmt_currency', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.BillAmt_rate', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.OrigTxnAmt_value', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.OrigTxnAmt_currency', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.OrigTxnAmt_partial', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.OrigTxnAmt_origItemId', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.File_filedate', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.File_filename', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.file_date', 'LIKE', "%$search%")
                        ->orWhere('agencybanking.file_name', 'LIKE', "%$search%")
                  
                        ;
                    }                    
            });
        }
    }

    public function getOneToOneMatchWithDate($bankStmt)
    {
        if(strpos($bankStmt->description, 'BGCFrom:') !== false)
            $descCmp = explode("BGCFrom:", $bankStmt->description);
        else
            $descCmp = explode("STOFrom:", $bankStmt->description);

        $isPayPal = false;

        if(strpos($bankStmt->description, 'PAYPAL') !== false)
        {
            $isPayPal = true;
        }
        //getting sortcode and account number in @desCmp[1];
        //IN desCamp[0] comes the name which is to be matched with agency banking External_name
        $descCmp2 = explode(" ", trim(@$descCmp[1]));
        //triming "-" from the sortcode
        $descCmp2[0] = str_replace("-", "", $descCmp2[0]);
        $amtMatch = 0;
        if(!empty($bankStmt->credit))
        {
            $amtMatch = $bankStmt->credit;
        }
        else
        {
            $amtMatch = $bankStmt->debit;
        }
        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids",DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS int_type"))->whereRaw("MATCH(agencybanking.External_name)AGAINST('".e($descCmp[0])."')")->where("agencybanking.External_sortcode",$descCmp2[0])->where("agencybanking.External_bankacc",$descCmp2[1])->where("agencybanking.SettlementDate",$bankStmt->date)->where('agencybanking.CashAmt_value',$amtMatch)
            ->where("agencybanking.CashType","RCP")
            ->where("agencybanking.CashCode_CashType","fpy")
            ->where('reco_flg','N')
            ->get();

        $dataArray = array();

        foreach ($agencybankingData as $key => $value) 
        {
            $cmpPart1 = 0;
            similar_text($value->External_name,@$descCmp[0],$cmpPart1);

            if($isPayPal)
            {
                if($cmpPart1 == 100)
                {
                    $dataArray = $value;

                }
                else
                {
                    if(strpos($bankStmt->description, 'CODE') !== false)
                    {
                        $cmpPart1 = 0;
                        similar_text($value->External_name." ".$value->External_name,trim(@$descCmp[0]),$cmpPart1);

                        if($cmpPart1 == 100)
                        {
                            $dataArray = $value;
                        }
                    }
                    else
                    {
                        $cmpPart1 = 0;
                        similar_text($value->External_name,trim(@$descCmp[0]),$cmpPart1);

                        if($cmpPart1 >= 25)
                        {
                            $dataArray = $value;
                        }
                    }

                    
                }
            }
            else
            {
                if($cmpPart1 >= 50)
                {
                    $dataArray = $value;

                }
            }
            
        }

        return $dataArray;
    }

    public function getOneToOneMatchWithOutDate($bankStmt)
    {
        if(strpos($bankStmt->description, 'BGCFrom:') !== false)
            $descCmp = explode("BGCFrom:", $bankStmt->description);
        else
            $descCmp = explode("STOFrom:", $bankStmt->description);

        $isPayPal = false;

        if(strpos($bankStmt->description, 'PAYPAL') !== false)
        {
            $isPayPal = true;
        }
        //GETTING sortcode and account number in @desCmp[1];
        //IN desCamp[0] comes the name which is to be matched with agency banking external_name
        $descCmp2 = explode(" ", trim(@$descCmp[1]));
          //triming "-" from the sortcode
        $descCmp2[0] = str_replace("-", "", $descCmp2[0]);
        $amtMatch = 0;
        if(!empty($bankStmt->credit))
        {
            $amtMatch = $bankStmt->credit;
        }
        else
        {
            $amtMatch = $bankStmt->debit;
        }

        $startDate = date("Y-m-d",strtotime("-6 day",strtotime($bankStmt->date)));
        //$endDate = $bankStmt->date;
        $endDate = date("Y-m-d",strtotime("-1 day",strtotime($bankStmt->date)));
        /*************************************************
         * We compare agency banking data using following variables
        1)-desCamp[0]---name compared with External_name
        2)-desCamp[2]---sortcode compared with external External_sourcecode
        3)-$amtMatch is the credit or debit amount in bankstatement which is to be matched with agency banking CashAmt_value
        ***************************************/
        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids",DB::raw("IF(agencybanking.banking_type='Approved','AB Approved','AB Declined') AS int_type"))
        ->whereRaw("MATCH(agencybanking.External_name)AGAINST('".e($descCmp[0])."')")
            ->where("agencybanking.External_sortcode",$descCmp2[0])
            ->where("agencybanking.External_bankacc",$descCmp2[1])
            ->whereBetween("agencybanking.SettlementDate",[$startDate,$endDate])
            ->where('agencybanking.CashAmt_value',$amtMatch)->where('reco_flg','N')
            ->where("agencybanking.CashType","RCP")
            ->where("agencybanking.CashCode_CashType","fpy")
            ->orderBy("agencybanking.SettlementDate","DESC")
            ->get();
        $dataArray = array();
        foreach ($agencybankingData as $key => $value) 
        {
            $cmpPart1 = 0;
            similar_text($value->External_name,@$descCmp[0],$cmpPart1);

            if($isPayPal)
            {
                if($cmpPart1 == 100)
                {
                    $dataArray = $value;
                }
                else
                {
                    if(strpos($bankStmt->description, 'CODE') !== false)
                    {
                        $cmpPart1 = 0;
                        similar_text($value->External_name." ".$value->External_name,trim(@$descCmp[0]),$cmpPart1);
                        if($cmpPart1 == 100)
                        {
                            $dataArray = $value;
                        }
                    }
                    else
                    {
                        $cmpPart1 = 0;
                        similar_text($value->External_name,trim(@$descCmp[0]),$cmpPart1);

                        if($cmpPart1 >= 25)
                        {
                            $dataArray = $value;
                        }
                    }
                }
            }
            //if there is no paypal in the description
            else
            {
                if($cmpPart1 >= 50)
                {
                    $dataArray = $value;
                }
            }
        }

        return $dataArray;
    }

    public function getAbDataByFpout($fpout , $startDate , $endDate)
    {
      
        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")
                            ->where("agencybanking.External_sortcode",$fpout->BeneficiaryCreditInstitution)
                            ->where("agencybanking.External_bankacc",$fpout->BeneficiaryCustomerAccountNumber)
                            ->whereBetween("agencybanking.SettlementDate",[$startDate,$endDate])
                            ->where('agencybanking.CashAmt_value',$fpout->Amount)
                            ->where('reco_flg','N')
                            ->orderBy("agencybanking.SettlementDate","DESC")
                            ->first();

        return $agencybankingData;
    }

    public function fetchABDataForFPOut($fpout,$type)
    {
        $endDate = date("Y-m-d",strtotime($fpout->file_date));
        $startDate = date("Y-m-d",strtotime("-4 day",strtotime($endDate)));

        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")
                            ->where("agencybanking.External_sortcode",$fpout->BeneficiaryCreditInstitution)
                            ->where("agencybanking.External_bankacc",$fpout->BeneficiaryCustomerAccountNumber)
                            ->whereBetween("agencybanking.SettlementDate",[$startDate,$endDate])
                            ->where('agencybanking.CashAmt_value',$fpout->Amount)
                            ->where('reco_flg','N')
                            ->where("banking_type",$type)
                            ->where("agencybanking.CashType","PAY")
                            ->where("agencybanking.CashCode_CashType","fpy")
                            ->orderBy("agencybanking.SettlementDate","ASC")
                            ->orderBy("agencybanking.File_filedate","ASC")
                            ->first();

        return $agencybankingData;
    }

    public function fetchABDDataForFPOut($fpout,$type)
    {
        $endDate = date("Y-m-d",strtotime($fpout->file_date));
        $startDate = date("Y-m-d",strtotime("-4 day",strtotime($endDate)));

        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")
                            ->where("agencybanking.External_sortcode",$fpout->BeneficiaryCreditInstitution)
                            ->where("agencybanking.External_bankacc",$fpout->BeneficiaryCustomerAccountNumber)
                            ->whereBetween("agencybanking.SettlementDate",[$startDate,$endDate])
                            ->where('agencybanking.CashAmt_value',$fpout->Amount)
                            ->where("banking_type",$type)
                            ->where("Desc","NOT LIKE","%//%")
                            ->where("agencybanking.fpout_dec_reco_flag","N")
                            ->where("agencybanking.CashType","RCP")
                            ->where("agencybanking.CashCode_CashType","fpy")
                            ->orderBy("agencybanking.SettlementDate","ASC")
                            ->orderBy("agencybanking.File_filedate","ASC")
                            ->first();

        return $agencybankingData;
    }

    public function fetchABDataForAdvice($fpout)
    {
        $dayName = date("D",strtotime($fpout->file_date));
        if($dayName == 'Fri')
        {
             $startDate = date("Y-m-d",strtotime("+3 day",strtotime($fpout->file_date)));
        }
        else
        {
            $startDate = date("Y-m-d",strtotime("+1 day",strtotime($fpout->file_date)));
        }
       

        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")
                            ->where("agencybanking.External_sortcode",$fpout->ext_bank_sort_code)
                            ->where("agencybanking.External_bankacc",$fpout->ext_bank_acc_number)
                            ->where("agencybanking.SettlementDate",$startDate)
                            ->where('agencybanking.CashAmt_value',$fpout->actual_amount)
                            ->where('reco_flg','N')
                            ->whereIn("agencybanking.CashType",["RCP"])
                            ->where("agencybanking.CashCode_CashType","bac")
                            ->orderBy("agencybanking.SettlementDate","DESC")
                            ->first();

        return $agencybankingData;
    }

    public function fetchReturnABDataForAdvice($fpout)
    {
        $dayName = date("D",strtotime($fpout->file_date));
        if($dayName == 'Fri')
        {
             $startDate = date("Y-m-d",strtotime("+3 day",strtotime($fpout->file_date)));
        }
        else
        {
            $startDate = date("Y-m-d",strtotime("+1 day",strtotime($fpout->file_date)));
        }
        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids")
                            ->where("agencybanking.External_sortcode",$fpout->ext_bank_sort_code)
                            ->where("agencybanking.External_bankacc",$fpout->ext_bank_acc_number)
                            ->where("agencybanking.SettlementDate",$startDate)
                            ->where('agencybanking.CashAmt_value',$fpout->actual_amount)
                            ->where('reco_flg','N')
                            ->whereIn("agencybanking.CashType",["RCPREV"])
                            ->where("agencybanking.CashCode_CashType","bac")
                            ->orderBy("agencybanking.SettlementDate","DESC")
                            ->first();

        return $agencybankingData;
    }

    public function oneToOneDDRMetchWithOutDate($bankStmt)
    {
        $amtMatch = 0;
        if(!empty($bankStmt->credit))
        {
            $amtMatch = $bankStmt->credit;
        }
        else
        {
            $amtMatch = $bankStmt->debit;
        }

        $agencybankingData = Agencybanking::select("agencybanking.*","agencybanking.id as ids",DB::raw(" 'DDR_Bacs' as int_type"))->where('agencybanking.CashAmt_value',$amtMatch)->where('reco_flg','N')->where("agencybanking.SettlementDate","<=",$bankStmt->date)->where("agencybanking.banking_type","Declined")->where("agencybanking.CashCode_CashType","bac")->orderBy("agencybanking.SettlementDate","DESC")->first();

        return $agencybankingData;
    }
}
