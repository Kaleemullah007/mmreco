<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardfeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cardfee', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('CardFeeId', 50)->index('CardFeeId');
			$table->string('LoadUnloadId', 50)->nullable();
			$table->dateTime('LocalDate')->nullable();
			$table->date('SettlementDate')->nullable()->index('SettlementDate');
			$table->string('TxId', 50)->nullable();
			$table->string('MerchCode', 50)->nullable();
			$table->text('Desc', 65535)->nullable();
			$table->string('ReasonCode', 50)->nullable();
			$table->string('FIID', 50)->nullable();
			$table->string('Card_productid', 50)->nullable();
			$table->string('Card_PAN', 50)->nullable()->index('Card_PAN');
			$table->string('Card_product', 50)->nullable();
			$table->string('Card_programid', 50)->nullable();
			$table->string('Card_branchcode', 50)->nullable();
			$table->string('Account_no', 50)->nullable();
			$table->string('Account_type', 50)->nullable();
			$table->string('TxnCode_direction', 50)->nullable();
			$table->string('TxnCode_Type', 50)->nullable();
			$table->string('TxnCode_Group', 50)->nullable();
			$table->string('TxnCode_ProcCode', 50)->nullable();
			$table->decimal('MsgSource_value', 20, 4)->nullable();
			$table->string('MsgSource_domesticMaestro', 50)->nullable();
			$table->string('FeeClass_interchangeTransaction', 50)->nullable();
			$table->string('FeeClass_type', 50)->nullable();
			$table->string('FeeClass_code', 50)->nullable();
			$table->string('FeeAmt_direction', 50)->nullable();
			$table->decimal('FeeAmt_value', 20, 4)->nullable();
			$table->string('FeeAmt_currency', 50)->nullable();
			$table->string('Amt_direction', 50)->nullable();
			$table->decimal('Amt_value', 20, 4)->nullable();
			$table->string('Amt_currency', 50)->nullable();
			$table->enum('reco_flg', array('N','Y'))->default('N');
			$table->timestamps();
			$table->date('file_date');
			$table->string('daily_balance_shift_id')->nullable();
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
		Schema::drop('cardfee');
	}

}
