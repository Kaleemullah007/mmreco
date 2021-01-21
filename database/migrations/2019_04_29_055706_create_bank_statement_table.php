<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankStatementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_statement', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->integer('bank_master_id');
			$table->date('date');
			$table->string('description');
			$table->string('type')->nullable();
			$table->decimal('debit', 15, 4)->nullable();
			$table->decimal('credit', 15, 4)->nullable();
			$table->decimal('bal', 15, 4)->nullable();
			$table->enum('reco_flg', array('N','Y'))->default('N');
			$table->string('extra_flags')->nullable();
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
		Schema::drop('bank_statement');
	}

}
