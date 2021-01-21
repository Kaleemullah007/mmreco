<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogtableTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logtable', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('msg')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('logtable');
	}

}
