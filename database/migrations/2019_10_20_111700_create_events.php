<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('status_id')->unsigned()->default('1');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->bigInteger('category_id')->unsigned()->default('1');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->double('longitude');
            $table->double('latitude');
            $table->text('eventName');
            $table->text('eventDescription');          
            $table->date('date');
            $table->dateTime('dateChange');
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
        Schema::dropIfExists('events');
    }
}
