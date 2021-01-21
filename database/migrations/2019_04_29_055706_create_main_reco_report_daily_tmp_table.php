<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMainRecoReportDailyTmpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('main_reco_report_daily_tmp', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->date('report_date');
			$table->decimal('diff_amt', 20, 4);
			$table->decimal('not_loaded_card_opening', 20, 4)->nullable();
			$table->decimal('ab_declined_not_loaded', 20, 4)->nullable();
			$table->decimal('not_matched_to_load', 20, 4)->nullable();
			$table->decimal('bacs_not_loaded_card', 20, 4)->nullable();
			$table->decimal('dca_adjustment', 20, 4)->nullable();
			$table->decimal('not_loaded_card_closing', 20, 4)->nullable();
			$table->decimal('app_fp_in_not_rec_current', 20, 4)->nullable();
			$table->decimal('app_fp_in_not_rec_closing', 20, 4)->nullable();
			$table->decimal('remain_fp_out_current', 20, 4)->nullable();
			$table->decimal('remain_fp_out_closing', 20, 4)->nullable();
			$table->decimal('bank_debits_not_match_current', 20, 4)->nullable();
			$table->decimal('bank_debits_not_match_closing', 20, 4)->nullable();
			$table->decimal('unmatch_bacs_ddr_current', 20, 4)->nullable();
			$table->decimal('unmatch_bacs_ddr_closing', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_current', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_recovered', 20, 4)->nullable();
			$table->decimal('unauthorized_dd_closing', 20, 4)->nullable();
			$table->decimal('balance_adj_current', 20, 4)->nullable();
			$table->decimal('balance_adj_closing', 20, 4)->nullable();
			$table->decimal('charge_back_current', 20, 4)->nullable();
			$table->decimal('charge_back_bal', 20, 4)->nullable();
			$table->decimal('return_to_source_adj', 20, 4)->nullable();
			$table->decimal('return_to_source_fp_out_sent', 20, 4)->nullable();
			$table->decimal('return_to_source_bal', 20, 4)->nullable();
			$table->decimal('blocked_amt_pos', 20, 4)->nullable();
			$table->decimal('blocked_amt_atm', 20, 4)->nullable();
			$table->decimal('blocked_amt_ofline_tt', 20, 4)->nullable();
			$table->decimal('blocked_amt_fee', 20, 4)->nullable();
			$table->decimal('blocked_amt_closing', 20, 4)->nullable();
			$table->decimal('net_diff', 20, 4)->nullable();
			$table->decimal('pos_interchang_current', 20, 4)->nullable();
			$table->decimal('pos_interchang_closing', 20, 4)->nullable();
			$table->decimal('atm_interchang_current', 20, 4)->nullable();
			$table->decimal('atm_interchang_closing', 20, 4)->nullable();
			$table->decimal('fp_out_fee', 20, 4)->nullable();
			$table->decimal('atm_fee', 20, 4)->nullable();
			$table->decimal('others_fee', 20, 4)->nullable();
			$table->decimal('closing_fee', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_curr', 20, 4)->nullable();
			$table->decimal('txn_sattled_not_adj_closing', 20, 4)->nullable();
			$table->decimal('adj_from_phy_vir_current', 20, 4)->nullable();
			$table->decimal('adj_from_phy_vir_closing', 20, 4)->nullable();
			$table->decimal('missing_gps_bal_current', 20, 4)->nullable();
			$table->decimal('missing_gps_bal_closing', 20, 4)->nullable();
			$table->decimal('bank_charges_current', 20, 4)->nullable();
			$table->decimal('bank_charges_closing', 20, 4)->nullable();
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
		Schema::drop('main_reco_report_daily_tmp');
	}

}
