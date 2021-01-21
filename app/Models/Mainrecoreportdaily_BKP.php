<?php
namespace App\Models;

use App\Models\ParexModel;
use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input;
use DB;

class Mainrecoreportdaily extends ParexModel
{

   //use SoftDeletes;

    protected $dates = [''];
    protected $table = 'main_reco_report_daily';

    protected $rules = array(
        'report_date'   => 'required',
    );

    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;

    public static function getDatatableData($params)
    {
        $Dailybalanceshift = Mainrecoreportdaily::select('main_reco_report_daily.*');
        if(Input::has('pan')){            
            $Dailybalanceshift = $Dailybalanceshift->where('pan' ,'=',e(Input::get('pan')));
        }
        if(isset($params['start_date']) && isset($params['end_date'])){            
            $Dailybalanceshift = $Dailybalanceshift->whereBetween('main_reco_report_daily.report_date' ,[$params['start_date'], $params['end_date']]);
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

        $allowed_columns = ['report_date','diff_amt','not_loaded_card_opening','ab_declined_not_loaded','not_matched_to_load','bacs_not_loaded_card','dca_adjustment','not_loaded_card_closing','app_fp_in_not_rec_current','app_fp_in_not_rec_closing','remain_fp_out_current','remain_fp_out_closing','bank_debits_not_match_current','bank_debits_not_match_closing','unmatch_bacs_ddr_current','unmatch_bacs_ddr_closing','unauthorized_dd_current','unauthorized_dd_recovered','unauthorized_dd_closing','balance_adj_current','balance_adj_closing','charge_back_current','charge_back_bal','return_to_source_adj','return_to_source_fp_out_sent','return_to_source_bal','blocked_amt_pos','blocked_amt_atm','blocked_amt_ofline_tt','blocked_amt_fee','blocked_amt_closing','net_diff','pos_interchang_current','pos_interchang_closing','atm_interchang_current','atm_interchang_closing','fp_out_fee','atm_fee','others_fee','closing_fee','txn_sattled_not_adj_curr','txn_sattled_not_adj_closing','adj_from_phy_vir_current','adj_from_phy_vir_closing','missing_gps_bal_current','missing_gps_bal_closing','bank_charges_current','bank_charges_closing','ultra_net','created_at'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'main_reco_report_daily.created_at';

        $Dailybalanceshift = $Dailybalanceshift->orderBy($sort, $order);
        
        $abCount = $Dailybalanceshift->get();
        $abCount = count($abCount);

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
                if(isset($filterArray['report_date']) && !empty($filterArray['report_date']))
                {
                    $query->where('report_date', 'LIKE', '%'.$filterArray['report_date'].'%');
                }

                if(isset($filterArray['diff_amt']) && !empty($filterArray['diff_amt']))
                {
                    $query->where('diff_amt', 'LIKE', '%'.$filterArray['diff_amt'].'%');
                }

                if(isset($filterArray['not_loaded_card_opening']) && !empty($filterArray['not_loaded_card_opening']))
                {
                    $query->where('not_loaded_card_opening', 'LIKE', '%'.$filterArray['not_loaded_card_opening'].'%');
                }

                if(isset($filterArray['ab_declined_not_loaded']) && !empty($filterArray['ab_declined_not_loaded']))
                {
                    $query->where('ab_declined_not_loaded', 'LIKE', '%'.$filterArray['ab_declined_not_loaded'].'%');
                }

                if(isset($filterArray['not_matched_to_load']) && !empty($filterArray['not_matched_to_load']))
                {
                    $query->where('not_matched_to_load', 'LIKE', '%'.$filterArray['not_matched_to_load'].'%');
                }

                if(isset($filterArray['bacs_not_loaded_card']) && !empty($filterArray['bacs_not_loaded_card']))
                {
                    $query->where('bacs_not_loaded_card', 'LIKE', '%'.$filterArray['bacs_not_loaded_card'].'%');
                }

                if(isset($filterArray['dca_adjustment']) && !empty($filterArray['dca_adjustment']))
                {
                    $query->where('dca_adjustment', 'LIKE', '%'.$filterArray['dca_adjustment'].'%');
                }

                if(isset($filterArray['not_loaded_card_closing']) && !empty($filterArray['not_loaded_card_closing']))
                {
                    $query->where('not_loaded_card_closing', 'LIKE', '%'.$filterArray['not_loaded_card_closing'].'%');
                }

                if(isset($filterArray['app_fp_in_not_rec_current']) && !empty($filterArray['app_fp_in_not_rec_current']))
                {
                    $query->where('app_fp_in_not_rec_current', 'LIKE', '%'.$filterArray['app_fp_in_not_rec_current'].'%');
                }

                if(isset($filterArray['app_fp_in_not_rec_closing']) && !empty($filterArray['app_fp_in_not_rec_closing']))
                {
                    $query->where('app_fp_in_not_rec_closing', 'LIKE', '%'.$filterArray['app_fp_in_not_rec_closing'].'%');
                }

                if(isset($filterArray['remain_fp_out_current']) && !empty($filterArray['remain_fp_out_current']))
                {
                    $query->where('remain_fp_out_current', 'LIKE', '%'.$filterArray['remain_fp_out_current'].'%');
                }

                if(isset($filterArray['remain_fp_out_closing']) && !empty($filterArray['remain_fp_out_closing']))
                {
                    $query->where('remain_fp_out_closing', 'LIKE', '%'.$filterArray['remain_fp_out_closing'].'%');
                }

                if(isset($filterArray['bank_debits_not_match_current']) && !empty($filterArray['bank_debits_not_match_current']))
                {
                    $query->where('bank_debits_not_match_current', 'LIKE', '%'.$filterArray['bank_debits_not_match_current'].'%');
                }

                if(isset($filterArray['bank_debits_not_match_closing']) && !empty($filterArray['bank_debits_not_match_closing']))
                {
                    $query->where('bank_debits_not_match_closing', 'LIKE', '%'.$filterArray['bank_debits_not_match_closing'].'%');
                }

                if(isset($filterArray['unmatch_bacs_ddr_current']) && !empty($filterArray['unmatch_bacs_ddr_current']))
                {
                    $query->where('unmatch_bacs_ddr_current', 'LIKE', '%'.$filterArray['unmatch_bacs_ddr_current'].'%');
                }

                if(isset($filterArray['unmatch_bacs_ddr_closing']) && !empty($filterArray['unmatch_bacs_ddr_closing']))
                {
                    $query->where('unmatch_bacs_ddr_closing', 'LIKE', '%'.$filterArray['unmatch_bacs_ddr_closing'].'%');
                }

                if(isset($filterArray['unauthorized_dd_current']) && !empty($filterArray['unauthorized_dd_current']))
                {
                    $query->where('unauthorized_dd_current', 'LIKE', '%'.$filterArray['unauthorized_dd_current'].'%');
                }

                if(isset($filterArray['unauthorized_dd_recovered']) && !empty($filterArray['unauthorized_dd_recovered']))
                {
                    $query->where('unauthorized_dd_recovered', 'LIKE', '%'.$filterArray['unauthorized_dd_recovered'].'%');
                }

                if(isset($filterArray['unauthorized_dd_closing']) && !empty($filterArray['unauthorized_dd_closing']))
                {
                    $query->where('unauthorized_dd_closing', 'LIKE', '%'.$filterArray['unauthorized_dd_closing'].'%');
                }

                if(isset($filterArray['balance_adj_current']) && !empty($filterArray['balance_adj_current']))
                {
                    $query->where('balance_adj_current', 'LIKE', '%'.$filterArray['balance_adj_current'].'%');
                }

                if(isset($filterArray['balance_adj_closing']) && !empty($filterArray['balance_adj_closing']))
                {
                    $query->where('balance_adj_closing', 'LIKE', '%'.$filterArray['balance_adj_closing'].'%');
                }

                if(isset($filterArray['charge_back_current']) && !empty($filterArray['charge_back_current']))
                {
                    $query->where('charge_back_current', 'LIKE', '%'.$filterArray['charge_back_current'].'%');
                }

                if(isset($filterArray['charge_back_bal']) && !empty($filterArray['charge_back_bal']))
                {
                    $query->where('charge_back_bal', 'LIKE', '%'.$filterArray['charge_back_bal'].'%');
                }

                if(isset($filterArray['return_to_source_adj']) && !empty($filterArray['return_to_source_adj']))
                {
                    $query->where('return_to_source_adj', 'LIKE', '%'.$filterArray['return_to_source_adj'].'%');
                }

                if(isset($filterArray['return_to_source_fp_out_sent']) && !empty($filterArray['return_to_source_fp_out_sent']))
                {
                    $query->where('return_to_source_fp_out_sent', 'LIKE', '%'.$filterArray['return_to_source_fp_out_sent'].'%');
                }

                if(isset($filterArray['return_to_source_bal']) && !empty($filterArray['return_to_source_bal']))
                {
                    $query->where('return_to_source_bal', 'LIKE', '%'.$filterArray['return_to_source_bal'].'%');
                }

                if(isset($filterArray['blocked_amt_pos']) && !empty($filterArray['blocked_amt_pos']))
                {
                    $query->where('blocked_amt_pos', 'LIKE', '%'.$filterArray['blocked_amt_pos'].'%');
                }

                if(isset($filterArray['blocked_amt_atm']) && !empty($filterArray['blocked_amt_atm']))
                {
                    $query->where('blocked_amt_atm', 'LIKE', '%'.$filterArray['blocked_amt_atm'].'%');
                }

                if(isset($filterArray['blocked_amt_ofline_tt']) && !empty($filterArray['blocked_amt_ofline_tt']))
                {
                    $query->where('blocked_amt_ofline_tt', 'LIKE', '%'.$filterArray['blocked_amt_ofline_tt'].'%');
                }

                if(isset($filterArray['blocked_amt_fee']) && !empty($filterArray['blocked_amt_fee']))
                {
                    $query->where('blocked_amt_fee', 'LIKE', '%'.$filterArray['blocked_amt_fee'].'%');
                }

                if(isset($filterArray['blocked_amt_closing']) && !empty($filterArray['blocked_amt_closing']))
                {
                    $query->where('blocked_amt_closing', 'LIKE', '%'.$filterArray['blocked_amt_closing'].'%');
                }

                if(isset($filterArray['net_diff']) && !empty($filterArray['net_diff']))
                {
                    $query->where('net_diff', 'LIKE', '%'.$filterArray['net_diff'].'%');
                }

                if(isset($filterArray['pos_interchang_current']) && !empty($filterArray['pos_interchang_current']))
                {
                    $query->where('pos_interchang_current', 'LIKE', '%'.$filterArray['pos_interchang_current'].'%');
                }

                if(isset($filterArray['pos_interchang_closing']) && !empty($filterArray['pos_interchang_closing']))
                {
                    $query->where('pos_interchang_closing', 'LIKE', '%'.$filterArray['pos_interchang_closing'].'%');
                }

                if(isset($filterArray['atm_interchang_current']) && !empty($filterArray['atm_interchang_current']))
                {
                    $query->where('atm_interchang_current', 'LIKE', '%'.$filterArray['atm_interchang_current'].'%');
                }

                if(isset($filterArray['atm_interchang_closing']) && !empty($filterArray['atm_interchang_closing']))
                {
                    $query->where('atm_interchang_closing', 'LIKE', '%'.$filterArray['atm_interchang_closing'].'%');
                }

                if(isset($filterArray['fp_out_fee']) && !empty($filterArray['fp_out_fee']))
                {
                    $query->where('fp_out_fee', 'LIKE', '%'.$filterArray['fp_out_fee'].'%');
                }

                if(isset($filterArray['atm_fee']) && !empty($filterArray['atm_fee']))
                {
                    $query->where('atm_fee', 'LIKE', '%'.$filterArray['atm_fee'].'%');
                }

                if(isset($filterArray['others_fee']) && !empty($filterArray['others_fee']))
                {
                    $query->where('others_fee', 'LIKE', '%'.$filterArray['others_fee'].'%');
                }

                if(isset($filterArray['closing_fee']) && !empty($filterArray['closing_fee']))
                {
                    $query->where('closing_fee', 'LIKE', '%'.$filterArray['closing_fee'].'%');
                }

                if(isset($filterArray['txn_sattled_not_adj_curr']) && !empty($filterArray['txn_sattled_not_adj_curr']))
                {
                    $query->where('txn_sattled_not_adj_curr', 'LIKE', '%'.$filterArray['txn_sattled_not_adj_curr'].'%');
                }

                if(isset($filterArray['txn_sattled_not_adj_closing']) && !empty($filterArray['txn_sattled_not_adj_closing']))
                {
                    $query->where('txn_sattled_not_adj_closing', 'LIKE', '%'.$filterArray['txn_sattled_not_adj_closing'].'%');
                }

                if(isset($filterArray['adj_from_phy_vir_current']) && !empty($filterArray['adj_from_phy_vir_current']))
                {
                    $query->where('adj_from_phy_vir_current', 'LIKE', '%'.$filterArray['adj_from_phy_vir_current'].'%');
                }

                if(isset($filterArray['adj_from_phy_vir_closing']) && !empty($filterArray['adj_from_phy_vir_closing']))
                {
                    $query->where('adj_from_phy_vir_closing', 'LIKE', '%'.$filterArray['adj_from_phy_vir_closing'].'%');
                }

                if(isset($filterArray['missing_gps_bal_current']) && !empty($filterArray['missing_gps_bal_current']))
                {
                    $query->where('missing_gps_bal_current', 'LIKE', '%'.$filterArray['missing_gps_bal_current'].'%');
                }

                if(isset($filterArray['missing_gps_bal_closing']) && !empty($filterArray['missing_gps_bal_closing']))
                {
                    $query->where('missing_gps_bal_closing', 'LIKE', '%'.$filterArray['missing_gps_bal_closing'].'%');
                }

                if(isset($filterArray['bank_charges_current']) && !empty($filterArray['bank_charges_current']))
                {
                    $query->where('bank_charges_current', 'LIKE', '%'.$filterArray['bank_charges_current'].'%');
                }

                if(isset($filterArray['bank_charges_closing']) && !empty($filterArray['bank_charges_closing']))
                {
                    $query->where('bank_charges_closing', 'LIKE', '%'.$filterArray['bank_charges_closing'].'%');
                }

                if(isset($filterArray['ultra_net']) && !empty($filterArray['ultra_net']))
                {
                    $query->where('ultra_net', 'LIKE', '%'.$filterArray['ultra_net'].'%');
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
                    $query->where(DB::raw("main_reco_report_daily.report_date"), 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.diff_amt', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.not_loaded_card_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.ab_declined_not_loaded', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.not_matched_to_load', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bacs_not_loaded_card', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dca_adjustment', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.not_loaded_card_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.app_fp_in_not_rec_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.app_fp_in_not_rec_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.remain_fp_out_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.remain_fp_out_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_debits_not_match_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_debits_not_match_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unmatch_bacs_ddr_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unmatch_bacs_ddr_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_recovered', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_bal', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_adj', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_fp_out_sent', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_bal', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_pos', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_atm', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_ofline_tt', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.net_diff', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_interchang_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_interchang_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fp_out_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.others_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.closing_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_curr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.adj_from_phy_vir_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.adj_from_phy_vir_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_gps_bal_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_gps_bal_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_charges_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_charges_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.ultra_net', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.created_at', 'LIKE', "%$search%")
                        ;
                    } 
            });
        }
    }

}
