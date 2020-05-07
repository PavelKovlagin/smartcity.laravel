<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('subname')->nullable();
            $table->date('date')->nullable();
            $table->string('email')->unique();
            $table->bigInteger('role_id')->unsigned();
            $table->string('code_reset_password')->nullable();
            $table->dateTime('validity_password_reset_code')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->date('blockDate')->default("0001-01-01 00:00:00");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
