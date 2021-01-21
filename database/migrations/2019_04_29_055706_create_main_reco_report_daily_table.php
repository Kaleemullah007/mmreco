<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMainRecoReportDailyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('main_reco_report_daily', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->date('report_date');
			$table->decimal('diff_amt', 20, 4);
			$table->decimal('opening_unclaim_fund', 20, 4)->nullable();
			$table->decimal('abd_opening', 20, 4)->nullable();
			$table->decimal('abd_fpreceived', 20, 4)->nullable();
			$table->decimal('abd_fpreturn', 20, 4)->nullable();
			$table->decimal('abd_bounceback', 20, 4)->nullable();
			$table->decimal('abd_bacsreceived', 20, 4)->nullable();
			$table->decimal('abd_bacsreturn', 20, 4)->nullable();
			$table->decimal('abd_closing', 20, 4)->nullable();
			$table->decimal('nmtl_fpinopening', 20, 4)->nullable();
			$table->decimal('nmtl_fpinreceived', 20, 4)->nullable();
			$table->decimal('nmtl_fpinreturn', 20, 4)->nullable();
			$table->decimal('nmtl_bounceback', 20, 4)->nullable();
			$table->decimal('nmtl_fpinclosing', 20, 4)->nullable();
			$table->decimal('nmtl_bacsopening', 20, 4)->nullable();
			$table->decimal('nmtl_bacsreceived', 20, 4)->nullable();
			$table->decimal('nmtl_bacsreturn', 20, 4)->nullable();
			$table->decimal('nmtl_bacsclosing', 20, 4)->nullable();
			$table->decimal('dcaadj_opening', 20, 4)->nullable();
			$table->decimal('dcaadj_dcaadj', 20, 4)->nullable();
			$table->decimal('dcaadj_adjtocard', 20, 4)->nullable();
			$table->decimal('dcaadj_closing', 20, 4)->nullable();
			$table->decimal('closing_unclaim_fund', 20, 4)->nullable();
			$table->decimal('fprp_opening', 20, 4)->nullable();
			$table->decimal('fprp_ppreceived', 20, 4)->nullable();
			$table->decimal('fprp_cdpipeline', 20, 4)->nullable();
			$table->decimal('fprp_closing', 20, 4)->nullable();
			$table->decimal('fpop_opening', 20, 4)->nullable();
			$table->decimal('fpop_pppaid', 20, 4)->nullable();
			$table->decimal('fpop_cdtrans', 20, 4)->nullable();
			$table->decimal('fpop_closing', 20, 4)->nullable();
			$table->decimal('umbd_opening', 20, 4)->nullable();
			$table->decimal('umbd_curr', 20, 4)->nullable();
			$table->decimal('umbd_adj', 20, 4)->nullable();
			$table->decimal('umbd_closing', 20, 4)->nullable();
			$table->decimal('unmatch_bacs_ddr_current', 20, 4)->nullable();
			$table->decimal('unmatch_bacs_ddr_adj', 20, 4)->nullable();
			$table->decimal('unmatch_bacs_ddr_closing', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_opening', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_current', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_recovered', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_closing', 20, 4)->nullable();
			$table->decimal('dec_dd_not_cr_opening', 20, 4)->nullable();
			$table->decimal('dec_dd_not_cr_uncr', 20, 4)->nullable();
			$table->decimal('dec_dd_not_cr_returned', 20, 4)->nullable();
			$table->decimal('dec_dd_not_cr_closing', 20, 4)->nullable();
			$table->decimal('missing_dd_opening', 20, 4)->nullable();
			$table->decimal('missing_dd_unknown', 20, 4)->nullable();
			$table->decimal('missing_dd_returned', 20, 4)->nullable();
			$table->decimal('missing_dd_closing', 20, 4)->nullable();
			$table->decimal('dd_closing_total', 20, 4)->nullable();
			$table->decimal('balance_adj_opening', 20, 4)->nullable();
			$table->decimal('balance_adj_credits', 20, 4)->nullable();
			$table->decimal('balance_adj_debits', 20, 4)->nullable();
			$table->decimal('balance_adj_closing', 20, 4)->nullable();
			$table->decimal('charge_back_opening', 20, 4)->nullable();
			$table->decimal('charge_back_credits', 20, 4)->nullable();
			$table->decimal('charge_back_debits', 20, 4)->nullable();
			$table->decimal('charge_back_closing', 20, 4)->nullable();
			$table->decimal('return_to_source_opening', 20, 4)->nullable();
			$table->decimal('return_to_source_adj', 20, 4)->nullable();
			$table->decimal('return_to_source_fp_out_sent', 20, 4)->nullable();
			$table->decimal('return_to_source_closing', 20, 4)->nullable();
			$table->decimal('blocked_amt_pos', 20, 4)->nullable();
			$table->decimal('blocked_amt_atm', 20, 4)->nullable();
			$table->decimal('blocked_amt_ofline_tt', 20, 4)->nullable();
			$table->decimal('blocked_amt_fee', 20, 4)->nullable();
			$table->decimal('blocked_amt_closing', 20, 4)->nullable();
			$table->decimal('pos_interchang_opening', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_dr', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_cb', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_re', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_cr', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_chargeback', 20, 4)->nullable();
			$table->decimal('pos_interchang_pos_repres', 20, 4)->nullable();
			$table->decimal('pos_interchang_closing', 20, 4)->nullable();
			$table->decimal('atm_interchang_opening', 20, 4)->nullable();
			$table->decimal('atm_interchang_current', 20, 4)->nullable();
			$table->decimal('atm_interchang_closing', 20, 4)->nullable();
			$table->decimal('fp_out_fee', 20, 4)->nullable();
			$table->decimal('atm_fee', 20, 4)->nullable();
			$table->decimal('forex_fee', 20, 4)->nullable();
			$table->decimal('card_replace_fee', 20, 4)->nullable();
			$table->decimal('code_999999_fee', 20, 4)->nullable();
			$table->decimal('closing_fee', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_opening', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_curr', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_prev', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_closing', 20, 4)->nullable();
			$table->decimal('adj_from_phy_vir_current', 20, 4)->nullable();
			$table->decimal('adj_from_phy_vir_closing', 20, 4)->nullable();
			$table->decimal('missing_gps_bal_current', 20, 4)->nullable();
			$table->decimal('missing_gps_bal_closing', 20, 4)->nullable();
			$table->decimal('bank_charges_current', 20, 4)->nullable();
			$table->decimal('bank_charges_closing', 20, 4)->nullable();
			$table->decimal('interst_current', 20, 4)->nullable();
			$table->decimal('interest_closing', 20, 4)->nullable();
			$table->decimal('ultra_net', 20, 4)->nullable();
			$table->timestamps();
			$table->enum('flag', array('N','Y'))->default('N');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('main_reco_report_daily');
	}

}
