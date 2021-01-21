<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardeventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cardevent', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('Card_PAN', 50)->nullable();
			$table->string('Card_productid', 50)->nullable();
			$table->string('Event_Type', 50)->nullable();
			$table->string('Event_Source', 50)->nullable();
			$table->dateTime('Event_ActivationDate')->nullable();
			$table->string('Event_StatCode', 50)->nullable();
			$table->string('Event_OldStatCode', 50)->nullable();
			$table->dateTime('Event_Date')->nullable();
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
		Schema::drop('cardevent');
	}

}
