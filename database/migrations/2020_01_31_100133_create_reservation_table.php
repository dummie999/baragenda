<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_id');
            $table->integer('shift_id'); //either shift or reservations 			
            $table->string('title');
            $table->text('description');
            $table->dateTime('datetime_start');
            $table->dateTime('datetime_end');
            $table->dateTime('recurring_start');
            $table->dateTime('recurring_end'); 
			$table->string('rrule');
            $table->integer('all_day');
            $table->integer('location_id');
            $table->integer('committee_id');
			$table->string('attendees');
			$table->string('status'); //draft published 
            $table->integer('updated_by');
            $table->timestamps();
			
			#foreign references
			$table->foreign('event_id')->references('id')->on('events');			
			$table->foreign('shift_id')->references('id')->on('shifts');			
			$table->foreign('location_id')->references('id')->on('locations');			
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
        Schema::dropIfExists('reservation');
    }
}
