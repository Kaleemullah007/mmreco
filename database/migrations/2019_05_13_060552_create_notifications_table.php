<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notification_id');
            $table->string('created_time');
            $table->integer('total_entries');
            $table->decimal('total_entries_sum', 11, 2);
            $table->integer('total_credit_entries');
            $table->decimal('total_credit_entries_sum', 11, 2);
            $table->integer('total_debit_entries');
            $table->decimal('total_debit_entries_sum', 11, 2);
            $table->string('party_account');
            $table->string('party_account_currency');
            
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
        Schema::dropIfExists('notifications');
    }
}
