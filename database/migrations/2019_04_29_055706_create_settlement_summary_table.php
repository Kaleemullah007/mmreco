<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettlementSummaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settlement_summary', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->date('settlement_date');
			$table->decimal('opening_ac_bal', 20, 4);
			$table->decimal('scheme_to_settlement_transfer', 20, 4)->nullable();
			$table->decimal('charges', 20, 4)->nullable();
			$table->decimal('deposits_into_settlement_ac', 20, 4)->nullable();
			$table->decimal('monthly_interest_settlement_ac', 20, 4)->nullable();
			$table->decimal('no_of_pos_txn', 20, 4);
			$table->decimal('value_of_pos_txn', 20, 4);
			$table->decimal('value_of_pos_interchange', 20, 4);
			$table->decimal('total_value_of_pos_txn', 20, 4);
			$table->decimal('number_of_atm_txn', 20, 4);
			$table->decimal('value_of_atm_txn', 20, 4);
			$table->decimal('value_of_atm_interchange', 20, 4);
			$table->decimal('total_value_of_atm_txn', 20, 4);
			$table->decimal('total_value_of_txn_settled', 20, 4);
			$table->decimal('settlement_closing_bal_adj', 20, 4)->nullable();
			$table->decimal('closing_ac_bal', 20, 4);
			$table->decimal('scheme_closing_bal', 20, 4);
			$table->decimal('dr_cr_bank', 20, 4)->nullable();
			$table->decimal('prefund', 20, 4)->nullable();
			$table->decimal('total_bal_available_to_cust_bal', 20, 4);
			$table->decimal('available_cust_bal_credit', 20, 4);
			$table->decimal('available_cust_bal_debit', 20, 4);
			$table->decimal('overall_cash_position', 20, 4);
			$table->decimal('live_pans', 20, 4);
			$table->decimal('transactional_fees', 20, 4)->nullable();
			$table->string('month', 10);
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
		Schema::drop('settlement_summary');
	}

}
