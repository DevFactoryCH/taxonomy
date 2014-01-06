<?php

use Illuminate\Database\Migrations\Migration;

class CreateTermRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('term_relations', function($table) {
			$table->increments('id');
			$table->integer('vocabulary_id')->unsigned();
			$table->integer('term_id')->unsigned();
			$table->integer('object_id')->unsigned(); 
			$table->string('object_type'); 

			$table->foreign('term_id')->references('id')->on('terms');
			$table->foreign('vocabulary_id')->references('id')->on('vocabularies');
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
		Schema::dropIfExists('term_relations');
	}

}	