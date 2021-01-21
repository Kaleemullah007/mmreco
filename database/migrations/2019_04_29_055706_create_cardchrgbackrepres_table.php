<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardchrgbackrepresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cardchrgbackrepres', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('RecordType', 50);
			$table->string('ChgbackRepresId', 50);
			$table->dateTime('LocalDate')->nullable();
			$table->date('SettlementDate')->nullable();
			$table->string('ApprCode', 50)->nullable();
			$table->string('MerchCode', 50)->nullable();
			$table->string('Schema', 50)->nullable();
			$table->string('Repeat', 50)->nullable();
			$table->string('ARN', 50)->nullable();
			$table->string('FIID', 50)->nullable();
			$table->string('RIID', 50)->nullable();
			$table->string('ReasonCode', 50)->nullable();
			$table->string('PartialReversal')->nullable();
			$table->string('Card_PAN', 50)->nullable();
			$table->string('Card_product', 50)->nullable();
			$table->string('Card_programid', 50)->nullable();
			$table->string('Card_branchcode', 50)->nullable();
			$table->string('Card_productid', 50)->nullable();
			$table->string('Account_no', 50)->nullable();
			$table->string('Account_type', 50)->nullable();
			$table->string('TxnCode_direction', 50)->nullable();
			$table->string('TxnCode_Type', 50)->nullable();
			$table->string('TxnCode_Group', 50)->nullable();
			$table->decimal('TxnAmt_value', 20, 4)->nullable();
			$table->string('TxnAmt_currency', 50)->nullable();
			$table->decimal('CashbackAmt_value', 20, 4)->nullable();
			$table->string('CashbackAmt_currency', 50)->nullable();
			$table->decimal('BillAmt_value', 20, 4)->nullable();
			$table->string('BillAmt_currency', 50)->nullable();
			$table->decimal('BillAmt_rate', 20, 4)->nullable();
			$table->string('Trace_auditno', 50)->nullable();
			$table->string('Trace_origauditno', 50)->nullable();
			$table->string('Trace_Retrefno', 50)->nullable();
			$table->string('Term_code', 50)->nullable();
			$table->string('Term_location', 50)->nullable();
			$table->string('Term_street', 50)->nullable();
			$table->string('Term_city', 50)->nullable();
			$table->string('Term_country', 50)->nullable();
			$table->string('Term_inputcapability', 50)->nullable();
			$table->string('Term_authcapability', 50)->nullable();
			$table->string('Txn_cardholderpresent', 50)->nullable();
			$table->string('Txn_cardpresent', 50)->nullable();
			$table->string('Txn_cardinputmethod', 50)->nullable();
			$table->string('Txn_cardauthmethod', 50)->nullable();
			$table->string('Txn_cardauthentity', 50)->nullable();
			$table->string('Txn_TVR', 50)->nullable();
			$table->string('MsgSource_value', 50)->nullable();
			$table->string('MsgSource_domesticMaestro', 50)->nullable();
			$table->decimal('SettlementAmt_value', 20, 4)->nullable();
			$table->string('SettlementAmt_currency', 50)->nullable();
			$table->decimal('SettlementAmt_rate', 20, 4)->nullable();
			$table->date('SettlementAmt_date')->nullable();
			$table->string('Fee_direction', 50)->nullable();
			$table->decimal('Fee_value', 20, 4)->nullable();
			$table->string('Fee_currency', 50)->nullable();
			$table->string('Classification_RCC', 50)->nullable();
			$table->string('Classification_MCC', 50)->nullable();
			$table->decimal('OrigTxnAmt_value', 20, 4)->nullable();
			$table->string('OrigTxnAmt_currency', 50)->nullable();
			$table->string('OrigTxnAmt_origItemId', 50)->nullable();
			$table->string('OrigTxnAmt_partial', 50)->nullable();
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
		Schema::drop('cardchrgbackrepres');
	}

}
