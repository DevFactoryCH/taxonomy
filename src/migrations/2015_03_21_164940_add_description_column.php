<?php

use Illuminate\Database\Migrations\Migration;

class AddDescriptionColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('terms', function ($table) {
            $table->string('description',1000);
        });  

        //
        Schema::table('term_relations', function ($table) {
            $table->string('description',1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('terms', function ($table) {
            $table->dropColumn('description');
        });  

        //
        Schema::table('term_relations', function ($table) {
            $table->dropColumn('description');
        });
    }

}