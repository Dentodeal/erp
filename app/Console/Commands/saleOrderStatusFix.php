<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class saleOrderStatusFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saleorders:fixstatus';

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
        $saleOrders = \App\SaleOrder::where('status','Awaiting Shipment')->get();
        $bar = $this->output->createProgressBar(count($saleOrders));
        $bar->start();
        foreach ($saleOrders as $saleOrder) {
            $model = \App\SaleOrder::find($saleOrder['id']);
            $model->status = 'Invoiced';
            $model->save();
            $bar->advance();
        }
        $bar->finish();
    }
}
