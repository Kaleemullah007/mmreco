<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBankBalanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_balance', function(Blueprint $table)
		{
			$table->string('id')->unique('id');
			$table->string('accno', 20);
			$table->string('currcode', 6)->nullable();
			$table->string('acctype', 5)->nullable();
			$table->string('sortcode', 10)->nullable();
			$table->string('bankacc', 20)->nullable();
			$table->string('feeband', 20)->nullable();
			$table->string('payment', 5)->nullable();
			$table->decimal('finamt', 20, 6)->nullable();
			$table->decimal('blkamt', 20, 6)->nullable();
			$table->decimal('amtavl', 20, 6)->nullable();
			$table->date('bankbal_date')->nullable()->index('bankbal_date');
			$table->timestamps();
			$table->softDeletes();
			$table->string('file_name')->nullable();
			$table->string('bank_pan')->nullable()->index('bank_pan');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bank_balance');
	}

}
