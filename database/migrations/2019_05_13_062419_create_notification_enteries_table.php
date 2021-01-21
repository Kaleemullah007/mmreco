<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationEnteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_enteries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notification_id');
            $table->string('notification_import_id');
            $table->decimal('amount', 11, 2);
            $table->string('credit_debit_indicator');
            $table->string('status');
            $table->date('booking_date');
            $table->string('booking_transaction_code');
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
        Schema::dropIfExists('notification_enteries');
    }
}
