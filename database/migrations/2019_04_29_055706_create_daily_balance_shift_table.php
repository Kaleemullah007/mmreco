<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyBalanceShiftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_balance_shift', function(Blueprint $table)
		{
			$table->string('id')->default('')->primary();
			$table->date('repot_date');
			$table->string('pan');
			$table->decimal('opening_ac_bal', 20, 4)->nullable();
			$table->decimal('ATM_Settled', 20, 4)->nullable();
			$table->decimal('POS_Settled', 20, 4)->nullable();
			$table->decimal('ATM_FEE', 20, 4)->nullable();
			$table->decimal('FPIN', 20, 4)->nullable();
			$table->decimal('Bacs_IN', 20, 4)->nullable();
			$table->decimal('FP_out', 20, 4)->nullable();
			$table->decimal('AB_DD', 20, 4)->nullable();
			$table->decimal('FP_out_fee', 20, 4)->nullable();
			$table->decimal('charge_backs', 20, 4)->nullable();
			$table->decimal('representments', 20, 4)->nullable();
			$table->decimal('Other_fees', 20, 4)->nullable();
			$table->decimal('Load_Unload', 20, 4)->nullable();
			$table->decimal('Blocked_Amount', 20, 4)->nullable();
			$table->decimal('Offline_Term_Trans', 20, 4)->nullable();
			$table->decimal('Balance_Adj', 20, 4)->nullable();
			$table->decimal('closing_ac_bal_calc', 20, 4)->nullable();
			$table->decimal('closing_ac_bal_gps', 20, 4)->nullable();
			$table->decimal('trans_settled_not_adj_gps', 20, 4)->nullable();
			$table->decimal('trans_settled_not_adj_gps_2', 20, 4)->nullable();
			$table->decimal('diff', 20, 4)->nullable();
			$table->timestamps();
			$table->enum('flag', array('N','Y'))->default('N');
			$table->index(['repot_date','pan'], 'repot_date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_balance_shift');
	}

}
