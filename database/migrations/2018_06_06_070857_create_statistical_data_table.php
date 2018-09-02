<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistical_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('test_id');
            $table->integer('points')->nullable();
            $table->dateTime('test_end_time');
            $table->boolean('test_complete');
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
        Schema::dropIfExists('statistical_data');
    }
}
