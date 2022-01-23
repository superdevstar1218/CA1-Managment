<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('month_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id') ;
            $table->bigInteger('project_id') ;
            $table->bigInteger('year') ;
            $table->string('period') ;
            $table->text('other') ;
            $table->Integer('isdone');
            $table->text('content') ;
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
        Schema::dropIfExists('month_schedules');
    }
}
