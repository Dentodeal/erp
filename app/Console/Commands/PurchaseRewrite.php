<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurchaseRewrite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchases:rewrite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rewrite Purchases to update taxable total etc.';

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
        $purchases = \App\Purchase::all();
        $bar = $this->output->createProgressBar(count($purchases));
        $bar->start();
        foreach($purchases as $purchase) {
            $model = \App\Purchase::find($purchase->id);
            $items = $model->items;
            $taxableTotal = 0;
            $taxRowTotal = 0;
            foreach($items as $item) 
            {
                $itemModel = \App\PurchaseItem::find($item['id']);
                if($item['gst'] == 0){
                    $product = \App\Product::find($item['product_id']);
                    $gst = $product->gst;
                }  else {
                    $gst = (float)$item['gst'];
                }
                $discount = $item['discount'] ? (float)$item['discount'] : 0;
                $taxable = ((float)$item['rate'] * (int)$item['qty']) * (1 - ($discount/100));
                $taxableTotal += $taxable;
                $taxAmount = ($taxable) * ($gst / 100);
                $taxRowTotal += $taxAmount;
                $itemModel->gst = $gst;
                $itemModel->taxable = $taxable;
                $itemModel->tax_amount = $taxAmount;
                $itemModel->save();
            }
            $model->taxable_total = $taxableTotal;
            $model->tax_row_total = $taxRowTotal;
            $model->tax_total = $taxRowTotal;
            $billFreightGst = 0;
            if(in_array($model->bill_freight_gst,[12,18,5])) {
                $billFreightGst = (float)$model->bill_freight * ((float)$model->bill_freight_gst / 100);
                $model->bill_freight_gst = $billFreightGst;
            } else {
                $billFreightGst = $model->bill_freight_gst ? (float)$model->bill_freight_gst : 0;
            }
            $subtotal = (float)$taxableTotal + (float)$billFreightGst + $taxRowTotal;
            $model->subtotal = round($subtotal,2);
            $billTotal = $subtotal - (float)$model->discount_subtotal + (float)$model->bill_freight + (float)$model->round;
            $model->bill_total = round($billTotal,2);
            $model->grand_total = round($billTotal + (float)$model->external_freight,2);
            $model->save();
            $bar->advance();
        }
        $bar->finish();
    }
}
