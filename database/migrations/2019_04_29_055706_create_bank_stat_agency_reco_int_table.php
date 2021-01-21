<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankStatAgencyRecoIntTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_stat_agency_reco_int', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('bank_statement_id');
			$table->string('related_table_id');
			$table->string('table_name');
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
		Schema::drop('bank_stat_agency_reco_int');
	}

}
