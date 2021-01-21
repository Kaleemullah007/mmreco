<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entery_id');
            $table->string('td_ref_txId');
            $table->string('td_ref_endtoend');
            $table->string('td_relp_dbtr_name');
           // $table->string('td_relp_dbtr_address');
            //$table->string('td_relp_dbtr_country');
            $table->string('td_relp_dbtr_account');
            $table->string('td_relp_cdtr_name');
           // $table->string('td_relp_cdtr_address');
           // $table->string('td_relp_cdtr_country');
            $table->string('td_relp_cdtr_account');
            $table->string('td_relp_party_tp');
            $table->string('td_relp_pty_party');
            $table->string('td_related_date');
            $table->string('td_additional_txn_info');
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
        Schema::dropIfExists('transaction_details');
    }
}
