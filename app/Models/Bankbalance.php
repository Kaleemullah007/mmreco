<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;
use Carbon\Carbon;

class Bankbalance extends ParexModel
{

   use SoftDeletes;

    protected $dates = [''];
    protected $table = 'bank_balance';

    protected $rules = array(
        'accno'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {

        $bankstatement = Bankbalance::select('bank_balance.*','bank_balance_card.*')
            ->join('bank_balance_card', 'bank_balance_card.bank_balance_id', '=', 'bank_balance.id')
            ->whereNull("bank_balance.deleted_at")            
            ->whereNull("bank_balance_card.deleted_at")            
            ;
        
        if(!isset($params['start_date']) && !isset($params['end_date'])){            
            $dt = Carbon::now();            
            $params['start_date'] = date('Y-m-d', strtotime($dt->subDays(15)));
            $params['end_date'] = date("Y-m-d");            
        }
        $bankstatement = $bankstatement->whereBetween('bank_balance.bankbal_date' ,[$params['start_date'], $params['end_date']]);

        if( Input::has('min') && Input::has('max') ){
            $bankstatement = $bankstatement->whereBetween('amtavl' ,[Input::get('min'),Input::get('max')]);
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

        $allowed_columns = ['accno','currcode','acctype','sortcode','bankacc','feeband','finamt','blkamt','amtavl','pan','virtual','primary','crdproduct','programid','custcode','statcode','expdate','crdaccno','crdcurrcode','productid','bankbal_date'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'bank_balance.bankbal_date';

        $bankstatement = $bankstatement->orderBy($sort, $order);
        
        
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

     public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['bankbal_date']) && !empty($filterArray['bankbal_date']))
                {
                    $query->where('bank_balance.bankbal_date', 'LIKE', '%'.$filterArray['bankbal_date'].'%');
                }

                if(isset($filterArray['accno']) && !empty($filterArray['accno']))
                {
                    $query->where('bank_balance.accno', 'LIKE', '%'.$filterArray['accno'].'%');
                }

                if(isset($filterArray['currcode']) && !empty($filterArray['currcode']))
                {
                    $query->where('bank_balance.currcode', 'LIKE', '%'.$filterArray['currcode'].'%');
                }

                if(isset($filterArray['acctype']) && !empty($filterArray['acctype']))
                {
                    $query->where('bank_balance.acctype', 'LIKE', '%'.$filterArray['acctype'].'%');
                }

                if(isset($filterArray['sortcode']) && !empty($filterArray['sortcode']))
                {
                    $query->where('bank_balance.sortcode', 'LIKE', '%'.$filterArray['sortcode'].'%');
                }

                if(isset($filterArray['bankacc']) && !empty($filterArray['bankacc']))
                {
                    $query->where('bank_balance.bankacc', 'LIKE', '%'.$filterArray['bankacc'].'%');
                }

                if(isset($filterArray['feeband']) && !empty($filterArray['feeband']))
                {
                    $query->where('bank_balance.feeband', 'LIKE', '%'.$filterArray['feeband'].'%');
                }

                if(isset($filterArray['finamt']) && !empty($filterArray['finamt']))
                {
                    $query->where('bank_balance.finamt', 'LIKE', '%'.$filterArray['finamt'].'%');
                }

                if(isset($filterArray['blkamt']) && !empty($filterArray['blkamt']))
                {
                    $query->where('bank_balance.blkamt', 'LIKE', '%'.$filterArray['blkamt'].'%');
                }

                if(isset($filterArray['amtavl']) && !empty($filterArray['amtavl']))
                {
                    $query->where('bank_balance.amtavl', 'LIKE', '%'.$filterArray['amtavl'].'%');
                }

                if(isset($filterArray['pan']) && !empty($filterArray['pan']))
                {
                    $query->where('bank_balance_card.pan', 'LIKE', '%'.$filterArray['pan'].'%');
                }

                if(isset($filterArray['virtual']) && !empty($filterArray['virtual']))
                {
                    $query->where('bank_balance_card.virtual', 'LIKE', '%'.$filterArray['virtual'].'%');
                }

                if(isset($filterArray['primary']) && !empty($filterArray['primary']))
                {
                    $query->where('bank_balance_card.primary', 'LIKE', '%'.$filterArray['primary'].'%');
                }

                if(isset($filterArray['crdproduct']) && !empty($filterArray['crdproduct']))
                {
                    $query->where('bank_balance_card.crdproduct', 'LIKE', '%'.$filterArray['crdproduct'].'%');
                }

                if(isset($filterArray['programid']) && !empty($filterArray['programid']))
                {
                    $query->where('bank_balance_card.programid', 'LIKE', '%'.$filterArray['programid'].'%');
                }

                if(isset($filterArray['custcode']) && !empty($filterArray['custcode']))
                {
                    $query->where('bank_balance_card.custcode', 'LIKE', '%'.$filterArray['custcode'].'%');
                }

                if(isset($filterArray['statcode']) && !empty($filterArray['statcode']))
                {
                    $query->where('bank_balance_card.statcode', 'LIKE', '%'.$filterArray['statcode'].'%');
                }

                if(isset($filterArray['expdate']) && !empty($filterArray['expdate']))
                {
                    $query->where('bank_balance_card.expdate', 'LIKE', '%'.$filterArray['expdate'].'%');
                }

                if(isset($filterArray['crdaccno']) && !empty($filterArray['crdaccno']))
                {
                    $query->where('bank_balance_card.crdaccno', 'LIKE', '%'.$filterArray['crdaccno'].'%');
                }

                if(isset($filterArray['crdcurrcode']) && !empty($filterArray['crdcurrcode']))
                {
                    $query->where('bank_balance_card.crdcurrcode', 'LIKE', '%'.$filterArray['crdcurrcode'].'%');
                }

                if(isset($filterArray['productid']) && !empty($filterArray['productid']))
                {
                    $query->where('bank_balance_card.productid', 'LIKE', '%'.$filterArray['productid'].'%');
                }

                if(isset($filterArray['created_at']) && !empty($filterArray['created_at']))
                {
                    $query->where('bank_balance.created_at', 'LIKE', '%'.$filterArray['created_at'].'%');
                }

                if(isset($filterArray['file_name']) && !empty($filterArray['file_name']))
                {
                    $query->where('bank_balance.file_name', 'LIKE', '%'.$filterArray['file_name'].'%');
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
                    $query->where('bank_balance.bankbal_date', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.accno', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.currcode', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.acctype', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.sortcode', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.bankacc', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.feeband', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.finamt', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.blkamt', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.amtavl', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.file_name', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.pan', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.virtual', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.primary', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.crdproduct', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.programid', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.custcode', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.statcode', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.expdate', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.crdaccno', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.crdcurrcode', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance_card.productid', 'LIKE',"%{$search}%")
                        ->orWhere('bank_balance.created_at', 'LIKE',"%{$search}%")
                        ;
                    }                    
            });
        }
    }



}
