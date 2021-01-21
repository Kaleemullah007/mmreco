<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardloadunloadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cardloadunload', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('RecordType', 50);
			$table->string('LoadUnloadId', 50);
			$table->dateTime('LocalDate')->nullable();
			$table->date('SettlementDate')->nullable();
			$table->string('MessageId', 50)->nullable();
			$table->string('MerchCode', 50)->nullable();
			$table->text('Desc', 65535)->nullable();
			$table->string('LoadSource', 50)->nullable();
			$table->string('LoadType', 50)->nullable();
			$table->string('VoidedLoadUnloadId', 50)->nullable();
			$table->string('Card_productid', 50)->nullable();
			$table->string('Card_PAN', 50)->nullable();
			$table->string('Card_product', 50)->nullable();
			$table->string('Card_programid', 50)->nullable();
			$table->string('Card_branchcode', 50)->nullable();
			$table->string('Account_no', 50)->nullable();
			$table->string('Account_type', 50)->nullable();
			$table->string('Amount_direction', 50)->nullable();
			$table->decimal('Amount_value', 20, 4)->nullable();
			$table->string('Amount_currency', 50)->nullable();
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
		Schema::drop('cardloadunload');
	}

}
