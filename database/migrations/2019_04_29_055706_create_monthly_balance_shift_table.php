<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMonthlyBalanceShiftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('monthly_balance_shift', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('report_month', 20);
			$table->string('pan');
			$table->decimal('opening_ac_bal', 20, 4)->nullable();
			$table->decimal('ATM_Settled', 20, 4)->nullable();
			$table->decimal('POS_Settled', 20, 4)->nullable();
			$table->decimal('ATM_FEE', 20, 4)->nullable();
			$table->decimal('FPIN', 20, 4)->nullable();
			$table->decimal('FP_out', 20, 4)->nullable();
			$table->decimal('FP_out_fee', 20, 4)->nullable();
			$table->decimal('Other_fees', 20, 4)->nullable();
			$table->decimal('Load_Unload', 20, 4)->nullable();
			$table->decimal('Blocked_Amount', 20, 4)->nullable();
			$table->decimal('Balance_Adj', 20, 4)->nullable();
			$table->decimal('closing_ac_bal_calc', 20, 4)->nullable();
			$table->decimal('closing_ac_bal_gps', 20, 4)->nullable();
			$table->decimal('Transactions_in_Timing', 20, 4)->nullable();
			$table->decimal('Transactions_in_Timing2', 20, 4)->nullable();
			$table->decimal('diff', 20, 4)->nullable();
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
		Schema::drop('monthly_balance_shift');
	}

}
