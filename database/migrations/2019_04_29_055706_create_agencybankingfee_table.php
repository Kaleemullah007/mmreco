<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgencybankingfeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agencybankingfee', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('BankingFeeId', 50)->index('BankingFeeId');
			$table->string('AbId', 50)->nullable();
			$table->date('SettlementDate')->nullable();
			$table->string('Desc')->nullable();
			$table->string('Card_PAN', 50)->nullable();
			$table->string('Card_productid', 50)->nullable();
			$table->string('Card_product', 50)->nullable();
			$table->string('Card_programid', 50)->nullable();
			$table->string('Card_branchcode', 50)->nullable();
			$table->string('AgencyAccount_no', 50)->nullable();
			$table->string('AgencyAccount_type', 50)->nullable();
			$table->string('AgencyAccount_sortcode', 50)->nullable();
			$table->string('AgencyAccount_bankacc', 50)->nullable();
			$table->string('AgencyAccount_name', 50)->nullable();
			$table->string('Amt_direction', 50)->nullable();
			$table->decimal('Amt_value', 20, 4)->nullable();
			$table->string('Amt_currency', 50)->nullable();
			$table->enum('reco_flg', array('N','Y'))->default('N');
			$table->timestamps();
			$table->date('file_date');
			$table->string('file_name')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agencybankingfee');
	}

}
