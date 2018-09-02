<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name','50');
            $table->boolean('answers_public');
            $table->boolean('passing_public');
            $table->text('description');
            $table->integer('duration')->nullable();
            $table->integer('user_id');
            $table->string('slug')->nullable()->index();
            $table->string('link_to_stat_if_no_reg')->nullable();
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
        Schema::dropIfExists('tests');
    }
}
