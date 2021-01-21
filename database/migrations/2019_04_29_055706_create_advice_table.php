<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdviceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('advice', function(Blueprint $table)
		{
			$table->string('id',255)->primary();
			$table->string('ab_sort_code', 25);
			$table->string('ab_account_number', 25);
			$table->string('code', 10);
			$table->string('ext_bank_sort_code', 25)->nullable();
			$table->string('ext_bank_acc_number', 25);
			$table->decimal('amount_in_cent', 15, 0)->nullable();
			$table->decimal('actual_amount', 20, 4)->nullable();
			$table->string('ext_name')->nullable();
			$table->enum('type', array('abapproved','abdeclined','dd','bacs'))->nullable();
			$table->string('related_table_id')->nullable();
			$table->date('file_date');
			$table->enum('reco_flg', array('Y','N'))->default('N');
			$table->timestamps();
			$table->string('file_name')->nullable();
			$table->string('C')->nullable();
			$table->string('A')->nullable();
			$table->string('ref')->nullable();
			$table->string('ab_name')->nullable();
			$table->string('advice_number')->nullable();
			$table->string('X')->nullable();
			$table->string('Y')->nullable();
			$table->string('Z')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('advice');
	}

}
