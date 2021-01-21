<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSkillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('skill', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('skill_name', 100);
			$table->enum('status', array('Active','InActive'))->default('Active');
			$table->integer('domain_id');
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
		Schema::drop('skill');
	}

}
