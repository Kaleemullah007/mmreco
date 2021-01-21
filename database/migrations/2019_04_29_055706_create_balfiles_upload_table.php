<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalfilesUploadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('balfiles_upload', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('filename');
			$table->string('file_pate');
			$table->boolean('upload_flg')->default(1);
			$table->date('file_date')->nullable();
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
		Schema::drop('balfiles_upload');
	}

}
