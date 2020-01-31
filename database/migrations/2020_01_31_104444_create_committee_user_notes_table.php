<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitteeUserNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_user_notes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('user_id'); //about user x
			$table->integer('committee_id'); //related to committee y
			$table->integer('updated_by'); //last updated by (from committee y)
			$table->text('note')-->nullable(); //note 65,535 characters (64kb)
			$table->boolean('private')->default(true); //visible for user
  
            $table->timestamps();
			
			#foreign references
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
			$table->foreign('committee_id')->references('id')->on('committee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('committee_user_notes');
    }
}
