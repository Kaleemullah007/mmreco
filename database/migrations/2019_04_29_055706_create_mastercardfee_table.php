<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMastercardfeeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mastercardfee', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('MastercardFeeId', 50);
			$table->string('MTID', 50)->nullable();
			$table->string('Function_Code_024', 50)->nullable();
			$table->string('Conversion_Rate_Reconciliation_009', 50)->nullable();
			$table->text('Additional_Data_048', 65535)->nullable();
			$table->dateTime('LocalDate')->nullable();
			$table->date('SettlementDate')->nullable();
			$table->text('Desc', 65535)->nullable();
			$table->string('ReasonCode', 50)->nullable();
			$table->text('Data_Record_072', 65535)->nullable();
			$table->string('DE93_Txn_Dest_ID', 50)->nullable();
			$table->string('DE94_Txn_Orig_ID', 50)->nullable();
			$table->string('File_ID_PDS0105', 50)->nullable();
			$table->text('FileProcessDate', 65535)->nullable();
			$table->string('FeeClass_interchangeTransaction', 50)->nullable();
			$table->string('FeeClass_type', 50)->nullable();
			$table->string('FeeClass_code', 50)->nullable();
			$table->string('FeeClass_memberID', 50)->nullable();
			$table->string('FeeAmt_direction', 50)->nullable();
			$table->decimal('FeeAmt_value', 20)->nullable();
			$table->string('FeeAmt_currency', 50)->nullable();
			$table->string('Amt_direction', 50)->nullable();
			$table->decimal('Amt_value', 20)->nullable();
			$table->string('Amt_currency', 50)->nullable();
			$table->string('Recon_date', 50)->nullable();
			$table->string('Recon_cycle', 50)->nullable();
			$table->string('Settlement_date', 50)->nullable();
			$table->string('Settlement_cycle', 50)->nullable();
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
		Schema::drop('mastercardfee');
	}

}
