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

        $allowed_columns = ['report_date','diff_amt','abd_opening','abd_fpreceived','abd_fpreturn','abd_bounceback','abd_bacsreceived','abd_bacsreturn','abd_closing','nmtl_fpinopening','nmtl_fpinreceived','nmtl_fpinreturn','nmtl_fpinclosing','nmtl_bacsopening','nmtl_bacsreceived','nmtl_bacsreturn','nmtl_bacsclosing','dcaadj_opening','dcaadj_dcaadj','dcaadj_adjtocard','dcaadj_closing','fprp_opening','fprp_ppreceived','fprp_cdpipeline','fprp_closing','fpop_opening','fpop_pppaid','fpop_cdtrans','fpop_closing','umbd_opening','umbd_curr','umbd_adj','umbd_closing','unmatch_bacs_ddr_current','unmatch_bacs_ddr_closing','unauthorized_dd_opening','unauthorized_dd_current','unauthorized_dd_recovered','unauthorized_dd_closing','dec_dd_not_cr_opening','dec_dd_not_cr_uncr','dec_dd_not_cr_returned','dec_dd_not_cr_closing','missing_dd_opening','missing_dd_unknown','missing_dd_returned','missing_dd_closing','balance_adj_opening','balance_adj_credits','balance_adj_debits','balance_adj_closing','charge_back_opening','charge_back_credits','charge_back_debits','charge_back_closing','return_to_source_opening','return_to_source_adj','return_to_source_fp_out_sent','return_to_source_closing','blocked_amt_pos','blocked_amt_atm','blocked_amt_ofline_tt','blocked_amt_fee','blocked_amt_closing','pos_interchang_opening','pos_interchang_pos_dr','pos_interchang_pos_cb','pos_interchang_pos_re','pos_interchang_pos_cr','pos_interchang_pos_chargeback','pos_interchang_pos_repres','pos_interchang_closing','atm_interchang_opening','atm_interchang_current','atm_interchang_closing','fp_out_fee','atm_fee','forex_fee','card_replace_fee','code_999999_fee','closing_fee','txn_sattled_not_adj_opening','txn_sattled_not_adj_curr','txn_sattled_not_adj_prev','txn_sattled_not_adj_closing','adj_from_phy_vir_current','adj_from_phy_vir_closing','missing_gps_bal_current','missing_gps_bal_closing','bank_charges_current','bank_charges_closing','interst_current','interest_closing','ultra_net','created_at','unmatch_bacs_ddr_adj'];

        $order = Input::get('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array(Input::get('sort'), $allowed_columns) ? e(Input::get('sort')) : 'main_reco_report_daily.report_date';

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
                if(isset($filterArray['report_date']) && !empty($filterArray['report_date']))
                {
                    $query->where('report_date', 'LIKE', '%'.$filterArray['report_date'].'%');
                }

                if(isset($filterArray['diff_amt']) && !empty($filterArray['diff_amt']))
                {
                    $query->where('diff_amt', 'LIKE', '%'.$filterArray['diff_amt'].'%');
                }

                if(isset($filterArray['abd_opening']) && !empty($filterArray['abd_opening']))
                {
                    $query->where('abd_opening', 'LIKE', '%'.$filterArray['abd_opening'].'%');
                }

                if(isset($filterArray['abd_fpreceived']) && !empty($filterArray['abd_fpreceived']))
                {
                    $query->where('abd_fpreceived', 'LIKE', '%'.$filterArray['abd_fpreceived'].'%');
                }

                if(isset($filterArray['abd_fpreturn']) && !empty($filterArray['abd_fpreturn']))
                {
                    $query->where('abd_fpreturn', 'LIKE', '%'.$filterArray['abd_fpreturn'].'%');
                }

                if(isset($filterArray['abd_bounceback']) && !empty($filterArray['abd_bounceback']))
                {
                    $query->where('abd_bounceback', 'LIKE', '%'.$filterArray['abd_bounceback'].'%');
                }

                if(isset($filterArray['abd_bacsreceived']) && !empty($filterArray['abd_bacsreceived']))
                {
                    $query->where('abd_bacsreceived', 'LIKE', '%'.$filterArray['abd_bacsreceived'].'%');
                }

                if(isset($filterArray['abd_bacsreturn']) && !empty($filterArray['abd_bacsreturn']))
                {
                    $query->where('abd_bacsreturn', 'LIKE', '%'.$filterArray['abd_bacsreturn'].'%');
                }

                if(isset($filterArray['abd_closing']) && !empty($filterArray['abd_closing']))
                {
                    $query->where('abd_closing', 'LIKE', '%'.$filterArray['abd_closing'].'%');
                }

                if(isset($filterArray['nmtl_fpinopening']) && !empty($filterArray['nmtl_fpinopening']))
                {
                    $query->where('nmtl_fpinopening', 'LIKE', '%'.$filterArray['nmtl_fpinopening'].'%');
                }

                if(isset($filterArray['nmtl_fpinreceived']) && !empty($filterArray['nmtl_fpinreceived']))
                {
                    $query->where('nmtl_fpinreceived', 'LIKE', '%'.$filterArray['nmtl_fpinreceived'].'%');
                }

                if(isset($filterArray['nmtl_fpinreturn']) && !empty($filterArray['nmtl_fpinreturn']))
                {
                    $query->where('nmtl_fpinreturn', 'LIKE', '%'.$filterArray['nmtl_fpinreturn'].'%');
                }

                if(isset($filterArray['nmtl_fpinclosing']) && !empty($filterArray['nmtl_fpinclosing']))
                {
                    $query->where('nmtl_fpinclosing', 'LIKE', '%'.$filterArray['nmtl_fpinclosing'].'%');
                }

                if(isset($filterArray['nmtl_bacsopening']) && !empty($filterArray['nmtl_bacsopening']))
                {
                    $query->where('nmtl_bacsopening', 'LIKE', '%'.$filterArray['nmtl_bacsopening'].'%');
                }

                if(isset($filterArray['nmtl_bacsreceived']) && !empty($filterArray['nmtl_bacsreceived']))
                {
                    $query->where('nmtl_bacsreceived', 'LIKE', '%'.$filterArray['nmtl_bacsreceived'].'%');
                }

                if(isset($filterArray['nmtl_bacsreturn']) && !empty($filterArray['nmtl_bacsreturn']))
                {
                    $query->where('nmtl_bacsreturn', 'LIKE', '%'.$filterArray['nmtl_bacsreturn'].'%');
                }

                if(isset($filterArray['nmtl_bacsclosing']) && !empty($filterArray['nmtl_bacsclosing']))
                {
                    $query->where('nmtl_bacsclosing', 'LIKE', '%'.$filterArray['nmtl_bacsclosing'].'%');
                }

                if(isset($filterArray['dcaadj_opening']) && !empty($filterArray['dcaadj_opening']))
                {
                    $query->where('dcaadj_opening', 'LIKE', '%'.$filterArray['dcaadj_opening'].'%');
                }

                if(isset($filterArray['dcaadj_dcaadj']) && !empty($filterArray['dcaadj_dcaadj']))
                {
                    $query->where('dcaadj_dcaadj', 'LIKE', '%'.$filterArray['dcaadj_dcaadj'].'%');
                }

                if(isset($filterArray['dcaadj_adjtocard']) && !empty($filterArray['dcaadj_adjtocard']))
                {
                    $query->where('dcaadj_adjtocard', 'LIKE', '%'.$filterArray['dcaadj_adjtocard'].'%');
                }

                if(isset($filterArray['dcaadj_closing']) && !empty($filterArray['dcaadj_closing']))
                {
                    $query->where('dcaadj_closing', 'LIKE', '%'.$filterArray['dcaadj_closing'].'%');
                }

                if(isset($filterArray['fprp_opening']) && !empty($filterArray['fprp_opening']))
                {
                    $query->where('fprp_opening', 'LIKE', '%'.$filterArray['fprp_opening'].'%');
                }

                if(isset($filterArray['fprp_ppreceived']) && !empty($filterArray['fprp_ppreceived']))
                {
                    $query->where('fprp_ppreceived', 'LIKE', '%'.$filterArray['fprp_ppreceived'].'%');
                }

                if(isset($filterArray['fprp_cdpipeline']) && !empty($filterArray['fprp_cdpipeline']))
                {
                    $query->where('fprp_cdpipeline', 'LIKE', '%'.$filterArray['fprp_cdpipeline'].'%');
                }

                if(isset($filterArray['fprp_closing']) && !empty($filterArray['fprp_closing']))
                {
                    $query->where('fprp_closing', 'LIKE', '%'.$filterArray['fprp_closing'].'%');
                }

                if(isset($filterArray['fpop_opening']) && !empty($filterArray['fpop_opening']))
                {
                    $query->where('fpop_opening', 'LIKE', '%'.$filterArray['fpop_opening'].'%');
                }

                if(isset($filterArray['fpop_pppaid']) && !empty($filterArray['fpop_pppaid']))
                {
                    $query->where('fpop_pppaid', 'LIKE', '%'.$filterArray['fpop_pppaid'].'%');
                }

                if(isset($filterArray['fpop_cdtrans']) && !empty($filterArray['fpop_cdtrans']))
                {
                    $query->where('fpop_cdtrans', 'LIKE', '%'.$filterArray['fpop_cdtrans'].'%');
                }

                if(isset($filterArray['fpop_closing']) && !empty($filterArray['fpop_closing']))
                {
                    $query->where('fpop_closing', 'LIKE', '%'.$filterArray['fpop_closing'].'%');
                }

                if(isset($filterArray['umbd_opening']) && !empty($filterArray['umbd_opening']))
                {
                    $query->where('umbd_opening', 'LIKE', '%'.$filterArray['umbd_opening'].'%');
                }

                if(isset($filterArray['umbd_curr']) && !empty($filterArray['umbd_curr']))
                {
                    $query->where('umbd_curr', 'LIKE', '%'.$filterArray['umbd_curr'].'%');
                }

                if(isset($filterArray['umbd_adj']) && !empty($filterArray['umbd_adj']))
                {
                    $query->where('umbd_adj', 'LIKE', '%'.$filterArray['umbd_adj'].'%');
                }

                if(isset($filterArray['umbd_closing']) && !empty($filterArray['umbd_closing']))
                {
                    $query->where('umbd_closing', 'LIKE', '%'.$filterArray['umbd_closing'].'%');
                }

                if(isset($filterArray['unmatch_bacs_ddr_current']) && !empty($filterArray['unmatch_bacs_ddr_current']))
                {
                    $query->where('unmatch_bacs_ddr_current', 'LIKE', '%'.$filterArray['unmatch_bacs_ddr_current'].'%');
                }

                if(isset($filterArray['unmatch_bacs_ddr_adj']) && !empty($filterArray['unmatch_bacs_ddr_adj']))
                {
                    $query->where('unmatch_bacs_ddr_adj', 'LIKE', '%'.$filterArray['unmatch_bacs_ddr_adj'].'%');
                }

                if(isset($filterArray['unmatch_bacs_ddr_closing']) && !empty($filterArray['unmatch_bacs_ddr_closing']))
                {
                    $query->where('unmatch_bacs_ddr_closing', 'LIKE', '%'.$filterArray['unmatch_bacs_ddr_closing'].'%');
                }

                if(isset($filterArray['unauthorized_dd_opening']) && !empty($filterArray['unauthorized_dd_opening']))
                {
                    $query->where('unauthorized_dd_opening', 'LIKE', '%'.$filterArray['unauthorized_dd_opening'].'%');
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

                if(isset($filterArray['dec_dd_not_cr_opening']) && !empty($filterArray['dec_dd_not_cr_opening']))
                {
                    $query->where('dec_dd_not_cr_opening', 'LIKE', '%'.$filterArray['dec_dd_not_cr_opening'].'%');
                }

                if(isset($filterArray['dec_dd_not_cr_uncr']) && !empty($filterArray['dec_dd_not_cr_uncr']))
                {
                    $query->where('dec_dd_not_cr_uncr', 'LIKE', '%'.$filterArray['dec_dd_not_cr_uncr'].'%');
                }

                if(isset($filterArray['dec_dd_not_cr_returned']) && !empty($filterArray['dec_dd_not_cr_returned']))
                {
                    $query->where('dec_dd_not_cr_returned', 'LIKE', '%'.$filterArray['dec_dd_not_cr_returned'].'%');
                }

                if(isset($filterArray['dec_dd_not_cr_closing']) && !empty($filterArray['dec_dd_not_cr_closing']))
                {
                    $query->where('dec_dd_not_cr_closing', 'LIKE', '%'.$filterArray['dec_dd_not_cr_closing'].'%');
                }

                if(isset($filterArray['missing_dd_opening']) && !empty($filterArray['missing_dd_opening']))
                {
                    $query->where('missing_dd_opening', 'LIKE', '%'.$filterArray['missing_dd_opening'].'%');
                }

                if(isset($filterArray['missing_dd_unknown']) && !empty($filterArray['missing_dd_unknown']))
                {
                    $query->where('missing_dd_unknown', 'LIKE', '%'.$filterArray['missing_dd_unknown'].'%');
                }

                if(isset($filterArray['missing_dd_returned']) && !empty($filterArray['missing_dd_returned']))
                {
                    $query->where('missing_dd_returned', 'LIKE', '%'.$filterArray['missing_dd_returned'].'%');
                }

                if(isset($filterArray['missing_dd_closing']) && !empty($filterArray['missing_dd_closing']))
                {
                    $query->where('missing_dd_closing', 'LIKE', '%'.$filterArray['missing_dd_closing'].'%');
                }

                if(isset($filterArray['balance_adj_opening']) && !empty($filterArray['balance_adj_opening']))
                {
                    $query->where('balance_adj_opening', 'LIKE', '%'.$filterArray['balance_adj_opening'].'%');
                }

                if(isset($filterArray['balance_adj_credits']) && !empty($filterArray['balance_adj_credits']))
                {
                    $query->where('balance_adj_credits', 'LIKE', '%'.$filterArray['balance_adj_credits'].'%');
                }

                if(isset($filterArray['balance_adj_debits']) && !empty($filterArray['balance_adj_debits']))
                {
                    $query->where('balance_adj_debits', 'LIKE', '%'.$filterArray['balance_adj_debits'].'%');
                }

                if(isset($filterArray['balance_adj_closing']) && !empty($filterArray['balance_adj_closing']))
                {
                    $query->where('balance_adj_closing', 'LIKE', '%'.$filterArray['balance_adj_closing'].'%');
                }

                if(isset($filterArray['charge_back_opening']) && !empty($filterArray['charge_back_opening']))
                {
                    $query->where('charge_back_opening', 'LIKE', '%'.$filterArray['charge_back_opening'].'%');
                }

                if(isset($filterArray['charge_back_credits']) && !empty($filterArray['charge_back_credits']))
                {
                    $query->where('charge_back_credits', 'LIKE', '%'.$filterArray['charge_back_credits'].'%');
                }

                if(isset($filterArray['charge_back_debits']) && !empty($filterArray['charge_back_debits']))
                {
                    $query->where('charge_back_debits', 'LIKE', '%'.$filterArray['charge_back_debits'].'%');
                }

                if(isset($filterArray['charge_back_closing']) && !empty($filterArray['charge_back_closing']))
                {
                    $query->where('charge_back_closing', 'LIKE', '%'.$filterArray['charge_back_closing'].'%');
                }

                if(isset($filterArray['return_to_source_opening']) && !empty($filterArray['return_to_source_opening']))
                {
                    $query->where('return_to_source_opening', 'LIKE', '%'.$filterArray['return_to_source_opening'].'%');
                }

                if(isset($filterArray['return_to_source_adj']) && !empty($filterArray['return_to_source_adj']))
                {
                    $query->where('return_to_source_adj', 'LIKE', '%'.$filterArray['return_to_source_adj'].'%');
                }

                if(isset($filterArray['return_to_source_fp_out_sent']) && !empty($filterArray['return_to_source_fp_out_sent']))
                {
                    $query->where('return_to_source_fp_out_sent', 'LIKE', '%'.$filterArray['return_to_source_fp_out_sent'].'%');
                }

                if(isset($filterArray['return_to_source_closing']) && !empty($filterArray['return_to_source_closing']))
                {
                    $query->where('return_to_source_closing', 'LIKE', '%'.$filterArray['return_to_source_closing'].'%');
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

                if(isset($filterArray['pos_interchang_opening']) && !empty($filterArray['pos_interchang_opening']))
                {
                    $query->where('pos_interchang_opening', 'LIKE', '%'.$filterArray['pos_interchang_opening'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_dr']) && !empty($filterArray['pos_interchang_pos_dr']))
                {
                    $query->where('pos_interchang_pos_dr', 'LIKE', '%'.$filterArray['pos_interchang_pos_dr'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_cb']) && !empty($filterArray['pos_interchang_pos_cb']))
                {
                    $query->where('pos_interchang_pos_cb', 'LIKE', '%'.$filterArray['pos_interchang_pos_cb'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_re']) && !empty($filterArray['pos_interchang_pos_re']))
                {
                    $query->where('pos_interchang_pos_re', 'LIKE', '%'.$filterArray['pos_interchang_pos_re'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_cr']) && !empty($filterArray['pos_interchang_pos_cr']))
                {
                    $query->where('pos_interchang_pos_cr', 'LIKE', '%'.$filterArray['pos_interchang_pos_cr'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_chargeback']) && !empty($filterArray['pos_interchang_pos_chargeback']))
                {
                    $query->where('pos_interchang_pos_chargeback', 'LIKE', '%'.$filterArray['pos_interchang_pos_chargeback'].'%');
                }

                if(isset($filterArray['pos_interchang_pos_repres']) && !empty($filterArray['pos_interchang_pos_repres']))
                {
                    $query->where('pos_interchang_pos_repres', 'LIKE', '%'.$filterArray['pos_interchang_pos_repres'].'%');
                }

                if(isset($filterArray['pos_interchang_closing']) && !empty($filterArray['pos_interchang_closing']))
                {
                    $query->where('pos_interchang_closing', 'LIKE', '%'.$filterArray['pos_interchang_closing'].'%');
                }

                if(isset($filterArray['atm_interchang_opening']) && !empty($filterArray['atm_interchang_opening']))
                {
                    $query->where('atm_interchang_opening', 'LIKE', '%'.$filterArray['atm_interchang_opening'].'%');
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

                if(isset($filterArray['forex_fee']) && !empty($filterArray['forex_fee']))
                {
                    $query->where('forex_fee', 'LIKE', '%'.$filterArray['forex_fee'].'%');
                }

                if(isset($filterArray['card_replace_fee']) && !empty($filterArray['card_replace_fee']))
                {
                    $query->where('card_replace_fee', 'LIKE', '%'.$filterArray['card_replace_fee'].'%');
                }

                if(isset($filterArray['code_999999_fee']) && !empty($filterArray['code_999999_fee']))
                {
                    $query->where('code_999999_fee', 'LIKE', '%'.$filterArray['code_999999_fee'].'%');
                }

                if(isset($filterArray['closing_fee']) && !empty($filterArray['closing_fee']))
                {
                    $query->where('closing_fee', 'LIKE', '%'.$filterArray['closing_fee'].'%');
                }

                if(isset($filterArray['txn_sattled_not_adj_opening']) && !empty($filterArray['txn_sattled_not_adj_opening']))
                {
                    $query->where('txn_sattled_not_adj_opening', 'LIKE', '%'.$filterArray['txn_sattled_not_adj_opening'].'%');
                }

                if(isset($filterArray['txn_sattled_not_adj_curr']) && !empty($filterArray['txn_sattled_not_adj_curr']))
                {
                    $query->where('txn_sattled_not_adj_curr', 'LIKE', '%'.$filterArray['txn_sattled_not_adj_curr'].'%');
                }

                if(isset($filterArray['txn_sattled_not_adj_prev']) && !empty($filterArray['txn_sattled_not_adj_prev']))
                {
                    $query->where('txn_sattled_not_adj_prev', 'LIKE', '%'.$filterArray['txn_sattled_not_adj_prev'].'%');
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

                if(isset($filterArray['interst_current']) && !empty($filterArray['interst_current']))
                {
                    $query->where('interst_current', 'LIKE', '%'.$filterArray['interst_current'].'%');
                }

                if(isset($filterArray['interest_closing']) && !empty($filterArray['interest_closing']))
                {
                    $query->where('interest_closing', 'LIKE', '%'.$filterArray['interest_closing'].'%');
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
                        ->orWhere('main_reco_report_daily.abd_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_fpreceived', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_fpreturn', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_bounceback', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_bacsreceived', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_bacsreturn', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.abd_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_fpinopening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_fpinreceived', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_fpinreturn', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_fpinclosing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_bacsopening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_bacsreceived', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_bacsreturn', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.nmtl_bacsclosing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dcaadj_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dcaadj_dcaadj', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dcaadj_adjtocard', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dcaadj_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fprp_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fprp_ppreceived', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fprp_cdpipeline', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fprp_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fpop_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fpop_pppaid', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fpop_cdtrans', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fpop_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.umbd_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.umbd_curr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.umbd_adj', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.umbd_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unmatch_bacs_ddr_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unmatch_bacs_ddr_adj', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unmatch_bacs_ddr_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_recovered', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.unauthorized_dd_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dec_dd_not_cr_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dec_dd_not_cr_uncr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dec_dd_not_cr_returned', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.dec_dd_not_cr_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_dd_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_dd_unknown', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_dd_returned', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_dd_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_credits', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_debits', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.balance_adj_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_credits', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_debits', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.charge_back_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_adj', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_fp_out_sent', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.return_to_source_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_pos', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_atm', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_ofline_tt', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.blocked_amt_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_dr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_cb', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_re', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_cr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_chargeback', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_pos_repres', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.pos_interchang_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_interchang_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_interchang_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_interchang_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.fp_out_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.atm_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.forex_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.card_replace_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.code_999999_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.closing_fee', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_opening', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_curr', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_prev', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.txn_sattled_not_adj_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.adj_from_phy_vir_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.adj_from_phy_vir_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_gps_bal_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.missing_gps_bal_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_charges_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.bank_charges_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.interst_current', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.interest_closing', 'LIKE', "%$search%")
                        ->orWhere('main_reco_report_daily.ultra_net', 'LIKE', "%$search%")

                        ->orWhere('main_reco_report_daily.created_at', 'LIKE', "%$search%")
                        ;
                    } 
            });
        }
    }

}
