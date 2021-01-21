<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('action_logs', function(Blueprint $table)
		{
			$table->integer('id')->unsigned();
			$table->integer('user_id')->nullable();
			$table->string('action_type');
			$table->integer('target_id')->nullable();
			$table->string('target_type')->nullable();
			$table->integer('location_id')->nullable();
			$table->text('note', 65535)->nullable();
			$table->text('filename', 65535)->nullable();
			$table->string('item_type');
			$table->integer('item_id');
			$table->date('expected_checkin')->nullable();
			$table->integer('accepted_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->integer('thread_id')->nullable();
			$table->integer('company_id')->nullable();
			$table->string('accept_signature', 100)->nullable();
			$table->binary('new_data', 16777215)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('action_logs');
	}

}
