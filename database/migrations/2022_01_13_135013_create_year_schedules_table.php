<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id') ;
            $table->bigInteger('project_id') ;
            $table->bigInteger('year') ;
            $table->text('comment') ;
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
        Schema::dropIfExists('year_schedules');
    }
}
