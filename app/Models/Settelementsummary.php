<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Settelementsummary extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'settlement_summary';

    protected $rules = array(
        'settlement_date'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $Settelementsummary = Settelementsummary::select('settlement_summary.*');
        
        if(isset($params['start_date']) && isset($params['end_date'])){            
            $Settelementsummary = $Settelementsummary->whereBetween('settlement_summary.settlement_date' ,[$params['start_date'], $params['end_date']]);
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
            $Settelementsummary = $Settelementsummary->TextSearch(Input::get('filter'),"filter");
        }
        else
        {
            if (Input::has('search')) {
                $Settelementsummary = $Settelementsummary->TextSearch(e(Input::get('search')),"search");
            }
        }

        $allowed_columns = ['settlement_date','opening_ac_bal','scheme_to_settlement_transfer','charges','deposits_into_settlement_ac','monthly_interest_settlement_ac','no_of_pos_txn','value_of_pos_txn','value_of_pos_interchange','total_value_of_pos_txn','number_of_atm_txn','value_of_atm_txn','value_of_atm_interchange','total_value_of_atm_txn','total_value_of_txn_settled','settlement_closing_bal_adj','closing_ac_bal','scheme_closing_bal','dr_cr_bank','prefund','total_bal_available_to_cust_bal','available_cust_bal_credit','available_cust_bal_debit','overall_cash_position','live_pans','transactional_fees','month'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'settlement_summary.settlement_date';

        $Settelementsummary = $Settelementsummary->orderBy($sort, $order);
        
        $ssCount = $Settelementsummary->count();
        // $ssCount = count($ssCount);

        if($limit != 0){            
            $Settelementsummary = $Settelementsummary->skip($offset)->take($limit)->get();
        }
        else{   
            $Settelementsummary = $Settelementsummary->get();
        }
     
        return array(
            'data' => $Settelementsummary,
            'count' => $ssCount
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
                if(isset($filterArray['settlement_date']) && !empty($filterArray['settlement_date']))
                {
                    $query->where('settlement_date', 'LIKE', '%'.$filterArray['settlement_date'].'%');
                }

                if(isset($filterArray['opening_ac_bal']) && !empty($filterArray['opening_ac_bal']))
                {
                    $query->where('opening_ac_bal', 'LIKE', '%'.$filterArray['opening_ac_bal'].'%');
                }

                if(isset($filterArray['scheme_to_settlement_transfer']) && !empty($filterArray['scheme_to_settlement_transfer']))
                {
                    $query->where('scheme_to_settlement_transfer', 'LIKE', '%'.$filterArray['scheme_to_settlement_transfer'].'%');
                }

                if(isset($filterArray['charges']) && !empty($filterArray['charges']))
                {
                    $query->where('charges', 'LIKE', '%'.$filterArray['charges'].'%');
                }

                if(isset($filterArray['deposits_into_settlement_ac']) && !empty($filterArray['deposits_into_settlement_ac']))
                {
                    $query->where('deposits_into_settlement_ac', 'LIKE', '%'.$filterArray['deposits_into_settlement_ac'].'%');
                }

                if(isset($filterArray['monthly_interest_settlement_ac']) && !empty($filterArray['monthly_interest_settlement_ac']))
                {
                    $query->where('monthly_interest_settlement_ac', 'LIKE', '%'.$filterArray['monthly_interest_settlement_ac'].'%');
                }

                if(isset($filterArray['no_of_pos_txn']) && !empty($filterArray['no_of_pos_txn']))
                {
                    $query->where('no_of_pos_txn', 'LIKE', '%'.$filterArray['no_of_pos_txn'].'%');
                }

                if(isset($filterArray['value_of_pos_txn']) && !empty($filterArray['value_of_pos_txn']))
                {
                    $query->where('value_of_pos_txn', 'LIKE', '%'.$filterArray['value_of_pos_txn'].'%');
                }

                if(isset($filterArray['value_of_pos_interchange']) && !empty($filterArray['value_of_pos_interchange']))
                {
                    $query->where('value_of_pos_interchange', 'LIKE', '%'.$filterArray['value_of_pos_interchange'].'%');
                }

                if(isset($filterArray['total_value_of_pos_txn']) && !empty($filterArray['total_value_of_pos_txn']))
                {
                    $query->where('total_value_of_pos_txn', 'LIKE', '%'.$filterArray['total_value_of_pos_txn'].'%');
                }

                if(isset($filterArray['number_of_atm_txn']) && !empty($filterArray['number_of_atm_txn']))
                {
                    $query->where('number_of_atm_txn', 'LIKE', '%'.$filterArray['number_of_atm_txn'].'%');
                }

                if(isset($filterArray['value_of_atm_txn']) && !empty($filterArray['value_of_atm_txn']))
                {
                    $query->where('value_of_atm_txn', 'LIKE', '%'.$filterArray['value_of_atm_txn'].'%');
                }

                if(isset($filterArray['value_of_atm_interchange']) && !empty($filterArray['value_of_atm_interchange']))
                {
                    $query->where('value_of_atm_interchange', 'LIKE', '%'.$filterArray['value_of_atm_interchange'].'%');
                }

                if(isset($filterArray['total_value_of_atm_txn']) && !empty($filterArray['total_value_of_atm_txn']))
                {
                    $query->where('total_value_of_atm_txn', 'LIKE', '%'.$filterArray['total_value_of_atm_txn'].'%');
                }

                if(isset($filterArray['total_value_of_txn_settled']) && !empty($filterArray['total_value_of_txn_settled']))
                {
                    $query->where('total_value_of_txn_settled', 'LIKE', '%'.$filterArray['total_value_of_txn_settled'].'%');
                }

                if(isset($filterArray['settlement_closing_bal_adj']) && !empty($filterArray['settlement_closing_bal_adj']))
                {
                    $query->where('settlement_closing_bal_adj', 'LIKE', '%'.$filterArray['settlement_closing_bal_adj'].'%');
                }

                if(isset($filterArray['closing_ac_bal']) && !empty($filterArray['closing_ac_bal']))
                {
                    $query->where('closing_ac_bal', 'LIKE', '%'.$filterArray['closing_ac_bal'].'%');
                }

                if(isset($filterArray['scheme_closing_bal']) && !empty($filterArray['scheme_closing_bal']))
                {
                    $query->where('scheme_closing_bal', 'LIKE', '%'.$filterArray['scheme_closing_bal'].'%');
                }

                if(isset($filterArray['dr_cr_bank']) && !empty($filterArray['dr_cr_bank']))
                {
                    $query->where('dr_cr_bank', 'LIKE', '%'.$filterArray['dr_cr_bank'].'%');
                }

                if(isset($filterArray['prefund']) && !empty($filterArray['prefund']))
                {
                    $query->where('prefund', 'LIKE', '%'.$filterArray['prefund'].'%');
                }

                if(isset($filterArray['total_bal_available_to_cust_bal']) && !empty($filterArray['total_bal_available_to_cust_bal']))
                {
                    $query->where('total_bal_available_to_cust_bal', 'LIKE', '%'.$filterArray['total_bal_available_to_cust_bal'].'%');
                }

                if(isset($filterArray['available_cust_bal_credit']) && !empty($filterArray['available_cust_bal_credit']))
                {
                    $query->where('available_cust_bal_credit', 'LIKE', '%'.$filterArray['available_cust_bal_credit'].'%');
                }

                if(isset($filterArray['available_cust_bal_debit']) && !empty($filterArray['available_cust_bal_debit']))
                {
                    $query->where('available_cust_bal_debit', 'LIKE', '%'.$filterArray['available_cust_bal_debit'].'%');
                }

                if(isset($filterArray['overall_cash_position']) && !empty($filterArray['overall_cash_position']))
                {
                    $query->where('overall_cash_position', 'LIKE', '%'.$filterArray['overall_cash_position'].'%');
                }

                if(isset($filterArray['live_pans']) && !empty($filterArray['live_pans']))
                {
                    $query->where('live_pans', 'LIKE', '%'.$filterArray['live_pans'].'%');
                }

                if(isset($filterArray['transactional_fees']) && !empty($filterArray['transactional_fees']))
                {
                    $query->where('transactional_fees', 'LIKE', '%'.$filterArray['transactional_fees'].'%');
                }

                if(isset($filterArray['month']) && !empty($filterArray['month']))
                {
                    $query->where('month', 'LIKE', '%'.$filterArray['month'].'%');
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
                    $query->where('settlement_date','LIKE',"%{$search}%")
                        ->orWhere('opening_ac_bal', 'LIKE',"%{$search}%")
                        ->orWhere('scheme_to_settlement_transfer', 'LIKE',"%{$search}%")
                        ->orWhere('charges', 'LIKE',"%{$search}%")
                        ->orWhere('deposits_into_settlement_ac', 'LIKE',"%{$search}%")
                        ->orWhere('monthly_interest_settlement_ac', 'LIKE',"%{$search}%")
                        ->orWhere('no_of_pos_txn', 'LIKE',"%{$search}%")
                        ->orWhere('value_of_pos_txn', 'LIKE',"%{$search}%")
                        ->orWhere('value_of_pos_interchange', 'LIKE',"%{$search}%")
                        ->orWhere('total_value_of_pos_txn', 'LIKE',"%{$search}%")                    
                        ->orWhere('number_of_atm_txn', 'LIKE',"%{$search}%")
                        ->orWhere('value_of_atm_txn', 'LIKE',"%{$search}%")
                        ->orWhere('value_of_atm_interchange', 'LIKE',"%{$search}%")
                        ->orWhere('total_value_of_atm_txn', 'LIKE',"%{$search}%")
                        ->orWhere('total_value_of_txn_settled', 'LIKE',"%{$search}%")
                        ->orWhere('settlement_closing_bal_adj', 'LIKE',"%{$search}%")
                        ->orWhere('closing_ac_bal', 'LIKE',"%{$search}%")
                        ->orWhere('scheme_closing_bal', 'LIKE',"%{$search}%")
                        ->orWhere('dr_cr_bank', 'LIKE',"%{$search}%")
                        ->orWhere('prefund', 'LIKE',"%{$search}%")
                        ->orWhere('total_bal_available_to_cust_bal', 'LIKE',"%{$search}%")
                        ->orWhere('available_cust_bal_credit', 'LIKE',"%{$search}%")
                        ->orWhere('available_cust_bal_debit', 'LIKE',"%{$search}%")
                        ->orWhere('overall_cash_position', 'LIKE',"%{$search}%")
                        ->orWhere('live_pans', 'LIKE',"%{$search}%")
                        ->orWhere('transactional_fees', 'LIKE',"%{$search}%")
                        ->orWhere('month', 'LIKE',"%{$search}%")
                        ;
                } 
            });
        }
    }

}
