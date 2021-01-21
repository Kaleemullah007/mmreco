<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyBalCardFinancialIntTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_bal_card_financial_int', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('daily_balance_shift_id');
			$table->string('cardfinancial_id');
			$table->enum('type', array('cardfinancial','cardfee'));
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_bal_card_financial_int');
	}

}
