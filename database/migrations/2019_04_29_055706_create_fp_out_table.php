<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFpOutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fp_out', function(Blueprint $table)
		{
			$table->string('id')->primary();
			$table->string('FileID');
			$table->date('file_date');
			$table->string('FPID')->nullable();
			$table->string('OrigCustomerSortCode', 20)->nullable();
			$table->string('OrigCustomerAccountNumber', 20)->nullable();
			$table->string('BeneficiaryCreditInstitution', 20)->nullable();
			$table->string('BeneficiaryCustomerAccountNumber', 20)->nullable();
			$table->decimal('Amount', 20, 4);
			$table->string('agencybanking_Id')->nullable();
			$table->string('Accepted', 50)->nullable();
			$table->string('ProcessedAsynchronously', 50)->nullable();
			$table->string('ReferenceInformation', 50)->nullable();
			$table->string('OrigCustomerAccountName', 50)->nullable();
			$table->enum('reco_flg', array('N','Y'))->default('N');
			$table->date('reco_date')->nullable();
			$table->timestamps();
			$table->enum('ab_type', array('Approved','Declined'))->nullable();
			$table->string('ReportTitle')->nullable();
			$table->string('CorporateID')->nullable();
			$table->string('SubmissionID')->nullable();
			$table->string('FPSDocumentTitle')->nullable();
			$table->string('FPSDocumentcreated')->nullable();
			$table->string('FPSDocumentschemaVersion')->nullable();
			$table->string('SubmissionStatus')->nullable();
			$table->string('Currency')->nullable();
			$table->string('FileStatus')->nullable();
			$table->string('OutwardAcceptedVolume')->nullable();
			$table->string('OutwardAcceptedValue')->nullable();
			$table->string('OutwardAcceptedValueCur')->nullable();
			$table->string('OutwardRejectedVolume')->nullable();
			$table->string('OutwardRejectedValue')->nullable();
			$table->string('OutwardRejectedValueCur')->nullable();
			$table->string('Time')->nullable();
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
		Schema::drop('fp_out');
	}

}
