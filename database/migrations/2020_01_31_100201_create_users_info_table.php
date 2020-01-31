<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('user_id');
			$table->string('username');
			$table->string('lidnummer');
			$table->string('relatienummer');
            $table->string('name');
			$table->json('groups'); //=memberOf
            #$table->string('mail'); //security issue?
            $table->timestamps();
			
			#foreign references
			$table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_info');
    }
}
