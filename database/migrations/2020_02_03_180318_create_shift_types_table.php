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
        Schema::create('shift_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('common')->default(false); //barco
            $table->integer('committee_id')->unsigned()->nullable(); //barco
            $table->string('title')->nullable(); //uitzit
            $table->string('description')->nullable(); //bardienst van 22:00-laat

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
