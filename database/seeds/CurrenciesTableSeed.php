<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'desc_ru' => 'Доллар США',
            'desc_eng' => 'US Dollar',
            'iso_num_code' => '840',
            'iso_char_code' => 'USD',
        ]);

        DB::table('currencies')->insert([
            'desc_ru' => 'Евро',
            'desc_eng' => 'Euro',
            'iso_num_code' => '978',
            'iso_char_code' => 'EUR',
        ]);
    }
}
