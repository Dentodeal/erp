<?php

use Illuminate\Database\Seeder;

class PricelistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Pricelist::create(['name'=>'Default','is_system'=>true]);
    }
}
