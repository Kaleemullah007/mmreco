<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankBalanceCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_balance_card', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('pan', 30)->index('pan');
			$table->string('virtual', 10)->nullable();
			$table->string('primary', 10)->nullable();
			$table->string('crdproduct', 15)->nullable();
			$table->string('programid', 15)->nullable();
			$table->string('custcode', 20)->nullable();
			$table->string('statcode', 10)->nullable();
			$table->date('expdate')->nullable();
			$table->string('crdaccno', 30)->nullable();
			$table->string('crdcurrcode', 10)->nullable();
			$table->string('productid', 10)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('bank_balance_id')->index('bank_balance_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_balance_card');
	}

}
