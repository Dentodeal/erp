<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class shipmentStatusGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment:generate';

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
        $saleOrders = \App\SaleOrder::where('status','Invoiced')->get();
        $bar = $this->output->createProgressBar(count($saleOrders));
        $bar->start();
        $nextDay = \Carbon\Carbon::today('Asia/Kolkata')->addHours(24)->toDateTimeString();
        $today = \Carbon\Carbon::today('Asia/Kolkata')->toDateTimeString();
        $today_date = \Carbon\Carbon::now()->format('ymd');
        foreach($saleOrders as $saleOrder) {
            $model = \App\SaleOrder::find($saleOrder['id']);
            if(!$model->shipment) {
                $todayShipmentsCount = \App\Shipment::whereBetween('created_at',[$today,$nextDay])->count();
                $serial_no = 'SH'.$today_date.str_pad($todayShipmentsCount, 4,0,STR_PAD_LEFT);
                $shipment = new \App\Shipment([
                    'serial_no' => $serial_no,
                    'created_by_id' => 1,
                    'status' => 'Awaiting Shipment',
                ]);
                $model->shipment()->save($shipment);
                $model->save();
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
