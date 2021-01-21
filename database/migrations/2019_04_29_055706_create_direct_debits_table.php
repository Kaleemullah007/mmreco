<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDirectDebitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('direct_debits', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->date('Processing_Date')->nullable();
			$table->date('Due_Date')->nullable();
			$table->string('SUN')->nullable();
			$table->string('Sun_Name')->nullable();
			$table->string('Trans_Code')->nullable();
			$table->string('DReference')->nullable();
			$table->string('diban')->nullable();
			$table->string('status', 30)->nullable();
			$table->decimal('amount', 20, 4)->nullable();
			$table->string('Token_Number')->nullable();
			$table->enum('reco_flg', array('Y','N'))->default('N');
			$table->date('reco_date')->nullable();
			$table->timestamps();
			$table->integer('deleted_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('direct_debits');
	}

}
