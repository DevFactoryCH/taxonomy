<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateForeignOnTermRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('term_relations', function(Blueprint $table)
		{
			$table->dropForeign('term_relations_term_id_foreign');
			$table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
			$table->dropForeign('term_relations_vocabulary_id_foreign');
			$table->foreign('vocabulary_id')->references('id')->on('vocabularies')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('term_relations', function(Blueprint $table)
		{
      $table->dropForeign('term_relations_term_id_foreign');
			$table->foreign('term_id')->references('id')->on('terms');
			$table->dropForeign('term_relations_vocabulary_id_foreign');
			$table->foreign('vocabulary_id')->references('id')->on('vocabularies');
		});
	}

}
