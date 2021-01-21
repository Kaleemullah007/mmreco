<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTxnMappingIntTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('txn_mapping_int', function(Blueprint $table)
		{
			$table->string('id');
			$table->string('bank_statement_id')->index('bank_statement_id');
			$table->string('txn_type')->index('txn_type');
			$table->string('txn_table_id')->index('txn_table_id');
			$table->string('coding')->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('txn_mapping_int');
	}

}
