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
			$table->integer('relationable_id')->unsigned();
			$table->string('relationable_type');
			$table->integer('term_id')->unsigned();
			$table->foreign('term_id')->references('id')->on('terms');
			$table->integer('vocabulary_id')->unsigned();
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