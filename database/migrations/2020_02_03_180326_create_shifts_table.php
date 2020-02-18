<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shift_type_id')->unsigned(); //bardienst van 22:00-laat (uitzit)
            $table->string('title'); //uitzit donderdag (dixo)
            $table->datetime('datetime'); //begint om donderdag 22:00
            $table->integer('length'); //8*60*60=28800 (8u) --> 6:00
            $table->string('description')->nullable(); //hippe beschrijving, wat is er te doen.
            $table->integer('updated_by')->unsigned(); //naam barcolid
			$table->timestamps();

			#foreign references
			$table->foreign('shift_types_id')->references('id')->on('shift_types');
			$table->foreign('updated_by')->references('id')->on('users');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
