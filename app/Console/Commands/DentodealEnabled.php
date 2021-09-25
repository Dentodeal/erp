<?php

namespace App\Console\Commands;

use App\Imports\DentodealEnabled as ImportsDentodealEnabled;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class DentodealEnabled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dentodeal:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting import');
        $array = (new ImportsDentodealEnabled)->toArray('skus_to_dentodeal_enabled.xlsx');
        $bar = $this->output->createProgressBar(count($array[0]));
        $count = 0;
        foreach($array[0] as $row){
            $product = \App\Product::where('sku',$row[0])->first();
            if($product) {
                $count += 1;
                $product->dentodeal_enabled = 1;
                $product->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info('\n');
        $this->info('Total Updated: '.$count);
        $this->info('\n');
        $this->info('Import complete');
    }
}
