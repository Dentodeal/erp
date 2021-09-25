<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateCosting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchases:costing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate each product costing';

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
        $purchases = \App\Purchase::whereIn('status',['Active','Admin Approved'])->get();
        $bar = $this->output->createProgressBar(count($purchases));
        $bar->start();
        foreach($purchases as $purchase) {
            $model = \App\Purchase::find($purchase->id);
            $model->freight_split_method = 'weight';
            $model->save();
            $model2 = \App\Purchase::find($purchase->id);
            $model2->calculateLanding();
            $bar->advance();
        }
        $bar->finish();
    }
}
