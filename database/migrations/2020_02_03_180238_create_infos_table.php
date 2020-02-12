<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned(); //internal id
			$table->string('objectGUID')->nullable(); //ldap GUID
			$table->string('lidnummer')->nullable(); //12-345
			$table->string('relatienummer')->nullable();//300000111231241
            $table->string('name'); //First Lastname
            $table->boolean('available')->default(true); //available
            $table->text('extra_info')->nullable(); //more info
			$table->json('groups')->nullable(); //=memberOf
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
        Schema::dropIfExists('infos');
    }
}
