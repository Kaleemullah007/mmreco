<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRejactedBacsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rejacted_bacs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->date('Date');
			$table->string('Token');
			$table->string('Sort_Code', 15);
			$table->string('Account', 20);
			$table->decimal('Txn_Amt', 20, 4);
			$table->string('return_b')->nullable();
			$table->string('Bacs_Return', 10)->nullable();
			$table->string('Txn_Code', 10);
			$table->string('Error_Code', 50)->nullable();
			$table->string('File_Description');
			$table->string('Failure_Reason');
			$table->string('BNK_BankAccountNumbersRef');
			$table->string('BNK_IncomingOutgoingBankFilesRef');
			$table->string('PANT');
			$table->string('PublicToken');
			$table->string('rej_bacs_id');
			$table->string('TransactionStatus', 20);
			$table->string('BNKTransID');
			$table->string('DestAccName_BACS');
			$table->string('IssuerID', 20);
			$table->string('Institution');
			$table->string('ActionCode', 20);
			$table->string('RecordType', 10);
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rejacted_bacs');
	}

}
