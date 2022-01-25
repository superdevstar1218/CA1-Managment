<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_logs', function (Blueprint $table) {
            $table->id();
            $table->date('received_date');
            $table->integer('customer_id');
            $table->string('currency');
            $table->string('amount')->default('0');
            $table->string('fee')->default('0');
            $table->int('real_pay')->default('0');
            $table->integer('project_id');
            $table->string('comment');
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
        Schema::dropIfExists('money_logs');
    }
}
