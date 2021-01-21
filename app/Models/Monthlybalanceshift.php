<?php
namespace App\Models;

use App\Models\ParexModel;
use App\Models\Dailybalanceshift;
use App\Models\Bankbalance;
use App\Models\Bankbalancecard;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Monthlybalanceshift extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'monthly_balance_shift';

    protected $rules = array(
        'report_month'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {    
        $dayName = date("D",strtotime($params['start_date']));
        $previousDate = "";

        if($dayName == 'Mon')
            $previousDate = date("Y-m-d",strtotime("-3 day",strtotime($params['start_date'])));
        else
            $previousDate = date("Y-m-d",strtotime("-1 day",strtotime($params['start_date'])));

        $openingBal = Dailybalanceshift::where("repot_date",$previousDate)->pluck('closing_ac_bal_gps', 'pan')->toArray();

        $closingacbalgps = Bankbalance::join("bank_balance_card","bank_balance_card.bank_balance_id","=","bank_balance.id")->whereNull("bank_balance_card.deleted_at")->where('bank_balance.bankbal_date',$params['start_date'])->pluck("bank_balance.finamt","bank_balance_card.pan")->toArray();

        $Dailybalanceshift = Dailybalanceshift::select("pan",DB::raw('SUM(ATM_Settled) as ATM_Settled'),DB::raw('SUM(POS_Settled) as POS_Settled'),DB::raw('SUM(ATM_FEE) as ATM_FEE'),DB::raw('SUM(FPIN) as FPIN'),DB::raw('SUM(FP_out) as FP_out'),DB::raw('SUM(FP_out_fee) as FP_out_fee'),DB::raw('SUM(Other_fees) as Other_fees'),DB::raw('SUM(Load_Unload) as Load_Unload'),DB::raw('SUM(Blocked_Amount) as Blocked_Amount'),DB::raw('SUM(Balance_Adj) as Balance_Adj'));

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

        $allowed_columns = ['pan','opening_ac_bal','ATM_Settled','POS_Settled','ATM_FEE','FPIN','FP_out','FP_out_fee','Other_fees','Load_Unload','Blocked_Amount','Balance_Adj','closing_ac_bal_calc','closing_ac_bal_gps','Transactions_in_Timing','Transactions_in_Timing2','diff'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'daily_balance_shift.created_at';

        $Dailybalanceshift = $Dailybalanceshift->orderBy($sort, $order);

        $Dailybalanceshift = $Dailybalanceshift->groupBy('daily_balance_shift.pan');

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
            'openingBal' => $openingBal,
            'closingacbalgps' => $closingacbalgps,
            'count' => $abCount
        );

    }

    // public static function getDatatableData($params)
    // {    	
    //    $Dailybalanceshift = Monthlybalanceshift::select('monthly_balance_shift.*');
    //     if(Input::has('pan')){            
    //         $Dailybalanceshift = $Dailybalanceshift->where('pan' ,'=',e(Input::get('pan')));
    //     }
    //     if(!isset($params['reportMonth'])){         
    //         $params['reportMonth'] = date('m-Y');                      
    //     }
    //     $Dailybalanceshift = $Dailybalanceshift->where('monthly_balance_shift.report_month' ,$params['reportMonth']);

    //     if (Input::has('offset')) {
    //         $offset = e(Input::get('offset'));
    //     } else {
    //         $offset = 0;
    //     }

    //     if (Input::has('limit')) {
    //         $limit = e(Input::get('limit'));
    //     } else {
    //         $limit = 50;
    //     }

    //     $sort = e(Input::get('sort'));

    //     // For Datatable Search & Filter
    //     if (Input::has('filter')) 
    //     {
    //         $Dailybalanceshift = $Dailybalanceshift->TextSearch(Input::get('filter'),"filter");
    //     }
    //     else
    //     {
    //         if (Input::has('search')) {
    //             $Dailybalanceshift = $Dailybalanceshift->TextSearch(e(Input::get('search')),"search");
    //         }
    //     }

    //     $allowed_columns = ['report_month','pan','opening_ac_bal','ATM_Settled','POS_Settled','ATM_FEE','FPIN','FP_out','FP_out_fee','Other_fees','Load_Unload','Blocked_Amount','Balance_Adj','closing_ac_bal_calc','closing_ac_bal_gps','Transactions_in_Timing','Transactions_in_Timing2','diff'];

    //     $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
    //     $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'monthly_balance_shift.created_at';

    //     $Dailybalanceshift = $Dailybalanceshift->orderBy($sort, $order);
        
    //     $abCount = $Dailybalanceshift->get();
    //     $abCount = count($abCount);

    //     if($limit != 0){            
    //         $Dailybalanceshift = $Dailybalanceshift->skip($offset)->take($limit)->get();
    //     }
    //     else{   
    //         $Dailybalanceshift = $Dailybalanceshift->get();
    //     }
     
    //     return array(
    //         'data' => $Dailybalanceshift,
    //         'count' => $abCount
    //     );

    // }


    public function scopeTextsearch($query, $search, $type)
    {
        if($type == "filter")
        {
            $filterArray = json_decode($search,true);
            return $query->where(function ($query) use ($filterArray) 
            {
                if(isset($filterArray['report_month']) && !empty($filterArray['report_month']))
                {
                    $query->where('report_month', 'LIKE', '%'.$filterArray['report_month'].'%');
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

                if(isset($filterArray['Transactions_in_Timing']) && !empty($filterArray['Transactions_in_Timing']))
                {
                    $query->where('Transactions_in_Timing', 'LIKE', '%'.$filterArray['Transactions_in_Timing'].'%');
                }

                if(isset($filterArray['Transactions_in_Timing2']) && !empty($filterArray['Transactions_in_Timing2']))
                {
                    $query->where('Transactions_in_Timing2', 'LIKE', '%'.$filterArray['Transactions_in_Timing2'].'%');
                }

                if(isset($filterArray['diff']) && !empty($filterArray['diff']))
                {
                    $query->where('diff', 'LIKE', '%'.$filterArray['diff'].'%');
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
                    $query->where(DB::raw("monthly_balance_shift.report_month"), 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.pan', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.opening_ac_bal', 'LIKE', "%$search%")             
                        ->orWhere('monthly_balance_shift.ATM_Settled', 'LIKE', "%$search%")                   
                        ->orWhere('monthly_balance_shift.POS_Settled', 'LIKE', "%$search%")                   
                        ->orWhere('monthly_balance_shift.ATM_FEE', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.FPIN', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.FP_out', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.FP_out_fee', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.Other_fees', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.Load_Unload', 'LIKE', "%$search%")                    
                        ->orWhere('monthly_balance_shift.Blocked_Amount', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.Balance_Adj', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.closing_ac_bal_calc', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.closing_ac_bal_gps', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.Transactions_in_Timing', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.Transactions_in_Timing2', 'LIKE', "%$search%")
                        ->orWhere('monthly_balance_shift.diff', 'LIKE', "%$search%")
                        ;
                    } 
            });
        }
    }

}
