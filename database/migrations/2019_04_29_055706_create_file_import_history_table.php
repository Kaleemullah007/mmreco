<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFileImportHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_import_history', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('file_name');
			$table->string('file_path');
			$table->string('module_name');
			$table->integer('imported_by')->nullable();
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
		Schema::drop('file_import_history');
	}

}
