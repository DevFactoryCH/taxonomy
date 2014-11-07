<?php

use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms', function($table) {
			$table->increments('id');
			$table->integer('vocabulary_id')->unsigned();
			$table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('cascade');
			$table->string('name');
      $table->integer('parent')->unsigned();
      $table->integer('weight');
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
		Schema::dropIfExists('terms');
	}

}