<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShipmentsFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decode tracking column to courier_id and tracking_number';

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
        $shipments = \App\Shipment::all();
        $bar = $this->output->createProgressBar(count($shipments));
        $bar->start();
        foreach ($shipments as $shipment) {
            $model = \App\Shipment::find($shipment['id']);
            $tracking = json_decode($model->tracking, true);
            if($tracking && array_key_exists('logistic', $tracking) && gettype($tracking['logistic']) == 'array') {
                // dd($tracking['logistic']['id']);
                $model->courier_id = $tracking['logistic']['id'];
                $model->tracking_number = $tracking['number'];
                $model->save();
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
