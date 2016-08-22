<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExchangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function(Blueprint $table) {
            $table->increments('id');
            $table->char('iso_char_code', 3);
            $table->dateTime('date');
            $table->decimal('rate', 8, 4);

            $table->foreign('iso_char_code')->references('iso_char_code')->on('currencies')->onDelete('cascade');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exchange_rates');
    }
}
