<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Dailybalanceshift extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'daily_balance_shift';
    public $incrementing = false;
    protected $rules = array(
        'repot_date'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public function cardfinancials()
    {
        return $this->belongsToMany("\App\Models\Cardfinancial","daily_bal_card_financial_int","daily_balance_shift_id","cardfinancial_id");
    }

    public static function getDatatableData($params)
    {
        $Dailybalanceshift = Dailybalanceshift::select('daily_balance_shift.*');
        if(Input::has('pan')){            
            $Dailybalanceshift = $Dailybalanceshift->where('pan' ,'=',e(Input::get('pan')));
        }
        if(isset($params['start_date']) && isset($params['end_date'])){            
            $Dailybalanceshift = $Dailybalanceshift->whereBetween('daily_balance_shift.repot_date' ,[$params['start_date'], $params['end_date']]);
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
            $Dailybalanceshift = $Dailybalanceshift->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $Dailybalanceshift = $Dailybalanceshift->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['repot_date','pan','opening_ac_bal','ATM_Settled','POS_Settled','ATM_FEE','FPIN','FP_out','FP_out_fee','Other_fees','Load_Unload','Blocked_Amount','Offline_Term_Trans','Balance_Adj','closing_ac_bal_calc','closing_ac_bal_gps','trans_settled_not_adj_gps','trans_settled_not_adj_gps_2','diff','charge_backs','representments','AB_DD','Bacs_IN'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'daily_balance_shift.created_at';

        $Dailybalanceshift = $Dailybalanceshift->orderBy($sort, $order);
        
        $abCount = $Dailybalanceshift->count();
        // $abCount = count($abCount);

        if($limit != 0){            
            $Dailybalanceshift = $Dailybalanceshift->skip($offset)->take($limit)->get();
        }
        else{   
            $Dailybalanceshift = $Dailybalanceshift->get();
        }
     
        return array(
            'data' => $Dailybalanceshift,
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
                if(isset($filterArray['repot_date']) && !empty($filterArray['repot_date']))
                {
                    $query->where('repot_date', 'LIKE', '%'.$filterArray['repot_date'].'%');
                }

                if(isset($filterArray['pan']) && !empty($filterArray['pan']))
                {
                    $query->where('pan', 'LIKE', '%'.$filterArray['pan'].'%');
                }

                if(isset($filterArray['opening_ac_bal']) && !empty($filterArray['opening_ac_bal']))
                {
                    $query->where('opening_ac_bal', 'LIKE', '%'.$filterArray['opening_ac_bal'].'%');
                }

                if(isset($filterArray['ATM_Settled']) && !empty($filterArray['ATM_Settled']))
                {
                    $query->where('ATM_Settled', 'LIKE', '%'.$filterArray['ATM_Settled'].'%');
                }

                if(isset($filterArray['POS_Settled']) && !empty($filterArray['POS_Settled']))
                {
                    $query->where('POS_Settled', 'LIKE', '%'.$filterArray['POS_Settled'].'%');
                }

                if(isset($filterArray['ATM_FEE']) && !empty($filterArray['ATM_FEE']))
                {
                    $query->where('ATM_FEE', 'LIKE', '%'.$filterArray['ATM_FEE'].'%');
                }

                if(isset($filterArray['FPIN']) && !empty($filterArray['FPIN']))
                {
                    $query->where('FPIN', 'LIKE', '%'.$filterArray['FPIN'].'%');
                }

                if(isset($filterArray['FP_out']) && !empty($filterArray['FP_out']))
                {
                    $query->where('FP_out', 'LIKE', '%'.$filterArray['FP_out'].'%');
                }

                if(isset($filterArray['FP_out_fee']) && !empty($filterArray['FP_out_fee']))
                {
                    $query->where('FP_out_fee', 'LIKE', '%'.$filterArray['FP_out_fee'].'%');
                }

                if(isset($filterArray['representments']) && !empty($filterArray['representments']))
                {
                    $query->where('representments', 'LIKE', '%'.$filterArray['representments'].'%');
                }

                if(isset($filterArray['charge_backs']) && !empty($filterArray['charge_backs']))
                {
                    $query->where('charge_backs', 'LIKE', '%'.$filterArray['charge_backs'].'%');
                }

                if(isset($filterArray['Other_fees']) && !empty($filterArray['Other_fees']))
                {
                    $query->where('Other_fees', 'LIKE', '%'.$filterArray['Other_fees'].'%');
                }

                if(isset($filterArray['Load_Unload']) && !empty($filterArray['Load_Unload']))
                {
                    $query->where('Load_Unload', 'LIKE', '%'.$filterArray['Load_Unload'].'%');
                }

                if(isset($filterArray['Blocked_Amount']) && !empty($filterArray['Blocked_Amount']))
                {
                    $query->where('Blocked_Amount', 'LIKE', '%'.$filterArray['Blocked_Amount'].'%');
                }

                if(isset($filterArray['Offline_Term_Trans']) && !empty($filterArray['Offline_Term_Trans']))
                {
                    $query->where('Offline_Term_Trans', 'LIKE', '%'.$filterArray['Offline_Term_Trans'].'%');
                }

                if(isset($filterArray['Balance_Adj']) && !empty($filterArray['Balance_Adj']))
                {
                    $query->where('Balance_Adj', 'LIKE', '%'.$filterArray['Balance_Adj'].'%');
                }

                if(isset($filterArray['closing_ac_bal_calc']) && !empty($filterArray['closing_ac_bal_calc']))
                {
                    $query->where('closing_ac_bal_calc', 'LIKE', '%'.$filterArray['closing_ac_bal_calc'].'%');
                }

                if(isset($filterArray['closing_ac_bal_gps']) && !empty($filterArray['closing_ac_bal_gps']))
                {
                    $query->where('closing_ac_bal_gps', 'LIKE', '%'.$filterArray['closing_ac_bal_gps'].'%');
                }

                if(isset($filterArray['trans_settled_not_adj_gps']) && !empty($filterArray['trans_settled_not_adj_gps']))
                {
                    $query->where('trans_settled_not_adj_gps', 'LIKE', '%'.$filterArray['trans_settled_not_adj_gps'].'%');
                }

                if(isset($filterArray['trans_settled_not_adj_gps_2']) && !empty($filterArray['trans_settled_not_adj_gps_2']))
                {
                    $query->where('trans_settled_not_adj_gps_2', 'LIKE', '%'.$filterArray['trans_settled_not_adj_gps_2'].'%');
                }

                if(isset($filterArray['diff']) && !empty($filterArray['diff']))
                {
                    $query->where('diff', 'LIKE', '%'.$filterArray['diff'].'%');
                }

                if(isset($filterArray['Bacs_IN']) && !empty($filterArray['Bacs_IN']))
                {
                    $query->where('Bacs_IN', 'LIKE', '%'.$filterArray['Bacs_IN'].'%');
                }

                if(isset($filterArray['AB_DD']) && !empty($filterArray['AB_DD']))
                {
                    $query->where('AB_DD', 'LIKE', '%'.$filterArray['AB_DD'].'%');
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
                    $query->where(DB::raw("daily_balance_shift.repot_date"), 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.pan', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.opening_ac_bal', 'LIKE', "%$search%")             
                        ->orWhere('daily_balance_shift.ATM_Settled', 'LIKE', "%$search%")                   
                        ->orWhere('daily_balance_shift.POS_Settled', 'LIKE', "%$search%")                   
                        ->orWhere('daily_balance_shift.ATM_FEE', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.FPIN', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.FP_out', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.FP_out_fee', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.charge_backs', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.representments', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.Other_fees', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.Load_Unload', 'LIKE', "%$search%")                    
                        ->orWhere('daily_balance_shift.Blocked_Amount', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.Offline_Term_Trans', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.Balance_Adj', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.closing_ac_bal_calc', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.closing_ac_bal_gps', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.trans_settled_not_adj_gps', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.trans_settled_not_adj_gps_2', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.Bacs_IN', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.AB_DD', 'LIKE', "%$search%")
                        ->orWhere('daily_balance_shift.diff', 'LIKE', "%$search%")
                        ;
                    } 
            });
        }
    }

}
