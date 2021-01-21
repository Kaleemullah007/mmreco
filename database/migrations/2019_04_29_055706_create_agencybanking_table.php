<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgencybankingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agencybanking', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->enum('banking_type', array('Approved','Declined'));
			$table->string('External_sortcode', 10);
			$table->string('External_bankacc', 20);
			$table->decimal('CashAmt_value', 20, 4);
			$table->string('CashType', 20);
			$table->string('BankingId', 20)->index('BankingId');
			$table->date('SettlementDate')->index('SettlementDate');
			$table->text('Desc', 65535)->nullable();
			$table->string('DeclineReason', 30)->nullable();
			$table->string('File_filedate', 25)->nullable();
			$table->text('File_filename', 65535)->nullable();
			$table->string('Card_PAN', 25)->index('Card_PAN');
			$table->string('Card_productid', 15)->nullable();
			$table->string('Card_product', 15)->nullable();
			$table->string('Card_programid', 15)->nullable();
			$table->string('Card_branchcode', 20)->nullable();
			$table->string('AgencyAccount_no', 20);
			$table->string('AgencyAccount_type', 5);
			$table->string('AgencyAccount_sortcode', 10);
			$table->string('AgencyAccount_bankacc', 20);
			$table->string('AgencyAccount_name', 30);
			$table->string('External_name', 30)->index('External_name');
			$table->string('CashCode_direction', 10)->nullable();
			$table->string('CashCode_CashType', 5)->nullable();
			$table->string('CashCode_CashGroup', 5)->nullable();
			$table->string('CashAmt_currency', 5);
			$table->string('Fee_direction', 10)->nullable();
			$table->decimal('Fee_value', 20, 4)->nullable();
			$table->string('Fee_currency', 5)->nullable();
			$table->decimal('BillAmt_value', 20, 4)->nullable();
			$table->string('BillAmt_currency', 5)->nullable();
			$table->decimal('BillAmt_rate', 20, 4)->nullable();
			$table->decimal('OrigTxnAmt_value', 20, 4)->nullable();
			$table->string('OrigTxnAmt_currency', 5)->nullable();
			$table->string('OrigTxnAmt_partial', 5)->nullable();
			$table->string('OrigTxnAmt_origItemId', 10)->nullable();
			$table->enum('reco_flg', array('N','Y','F','A'))->default('N');
			$table->date('reco_date')->nullable();
			$table->date('fp_out_date')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->date('file_date');
			$table->string('file_name')->nullable();
			$table->enum('fpout_dec_reco_flag', array('N','Y'))->default('N');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agencybanking');
	}

}
