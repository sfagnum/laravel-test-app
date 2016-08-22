<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Currencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('currencies', function(Blueprint $table) {
           $table->char('iso_char_code', 3)->unique(); //ISO 3166-1 alpha-3
           $table->integer('iso_num_code')->unique();
           $table->string('desc_ru');
           $table->string('desc_eng');

           $table->primary('iso_num_code');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('currencies');
    }
}
