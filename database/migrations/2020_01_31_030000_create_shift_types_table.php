<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('committee_id')->unsigned(); //barco
            $table->string('title'); //uitzit
            $table->string('description'); //bardienst van 22:00-laat
            $table->integer('updated_by')->unsigned(); //lid barco
            $table->timestamps();
			
			#foreign references
			$table->foreign('committee_id')->references('id')->on('committees');
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
        Schema::dropIfExists('shift_types');
    }
}
