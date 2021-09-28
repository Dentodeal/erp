<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class settleDentodealOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dentodeal:settle {date}';

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
        $saleOrders = \App\SaleOrder::where('source','Dentodeal')
        ->where('payment_status','<>','Settled')
        ->where('invoiced_at','<',$this->argument('date'))
        ->get();
        $bar = $this->output->createProgressBar(count($saleOrders));

        $bar->start();

        foreach($saleOrders as $saleOrder) {
            $saleOrder->settleBy($saleOrder->total,'Bank','','Dentodeal Write Off by system');
            $bar->advance();
        }
        $bar->finish();
        $this->line('\n');
        $this->info('Settlement Complete');

    }
}
