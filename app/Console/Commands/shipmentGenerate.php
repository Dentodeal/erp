<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class shipmentGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipment:create {id}';

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
        $soId = $this->argument('id');
        $model = \App\SaleOrder::find($soId);
        if(!$model->shipment) {
            $nextDay = \Carbon\Carbon::today('Asia/Kolkata')->addHours(24)->toDateTimeString();
            $today = \Carbon\Carbon::today('Asia/Kolkata')->toDateTimeString();
            $today_date = \Carbon\Carbon::now()->format('ymd');
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
    }
}
