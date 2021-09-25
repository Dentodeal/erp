<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Config::firstOrCreate([
            'label' => 'back_accounts',
        ],[
            'value' => [
                [
                    'name' => 'Apexion ICICI',
                    'acc_name' => 'Apexion Dental Products and Services',
                    'bank_name' => 'ICICI Bank Ltd.',
                    'acc_no' => '060305500009',
                    'ifsc' => 'ICIC0000603',
                    'upi' => 'apexiondental@icici'
                ]
            ]
        ]);
    }
}
