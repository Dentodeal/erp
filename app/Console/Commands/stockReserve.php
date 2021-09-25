<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class stockReserve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:reserve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reserve Stock for draft orders';

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
        $saleOrders = \App\SaleOrder::where('status','Draft')->get();
        $bar = $this->output->createProgressBar(count($saleOrders));
        $bar->start();
        foreach($saleOrders as $saleOrder) {
            $model = \App\SaleOrder::find($saleOrder['id']);
            \App\SaleOrder::reserveStock($model,$model->items);
            $bar->advance();
        }
        $bar->finish();
    }
}
