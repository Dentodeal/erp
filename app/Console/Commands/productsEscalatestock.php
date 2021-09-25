<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class productsEscalatestock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:escalatestock';

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
        $products = \App\Product::all();
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();
        foreach ($products as $product) {
            $m = \App\ProductStock::where([
                ['product_id','=', $product['id']],
                ['reservable_id','=', 0],
                ['expiry_date','=', NULL],
                ['warehouse_id','=',1]
            ])->first();
            if($m) {
                $m->update(['qty' => 10000]);
            } else {
                \App\ProductStock::create([
                    'product_id' => $product['id'],
                    'reservable_id' => 0,
                    'expiry_date' => NULL,
                    'qty' => 10000,
                    'warehouse_id' => 1
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
