<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class dentodealStockUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dentodeal:update';

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
        Log::info('dentodeal:update command invoked at '.\Carbon\Carbon::now()->toString());
        $products = [];
        $today = \Carbon\Carbon::today('Asia/Kolkata')->toDateTimeString();
        $tomorrow = \Carbon\Carbon::today('Asia/Kolkata')->addHours(24)->toDateTimeString();
        $saleOrders = \App\SaleOrder::whereBetween('updated_at',[$today,$tomorrow])->get();
        $grns = \App\GoodsReceiveNote::whereBetween('updated_at',[$today,$tomorrow])->get();
        $saleReturns = \App\SaleReturn::whereBetween('updated_at',[$today,$tomorrow])->get();
        $purchaseReturns = \App\PurchaseReturn::whereBetween('updated_at',[$today,$tomorrow])->get();
        foreach($saleOrders as $saleOrder){
            $items = $saleOrder->items;
            foreach($items as $item){
                if(!in_array($item->product_id,$products)) {
                    array_push($products,$item->product_id);
                }
            }
        }
        foreach($grns as $grn){
            $items = $grn->items;
            foreach($items as $item){
                foreach($items as $item){
                    if(!in_array($item->product_id,$products)) {
                        array_push($products,$item->product_id);
                    }
                }
            }
        }
        foreach($saleReturns as $saleReturn){
            $items = $saleReturn->items;
            foreach($items as $item){
                foreach($items as $item){
                    if(!in_array($item->product_id,$products)) {
                        array_push($products,$item->product_id);
                    }
                }
            }
        }
        foreach($purchaseReturns as $purchaseReturn){
            $items = $purchaseReturn->items;
            foreach($items as $item){
                foreach($items as $item){
                    if(!in_array($item->product_id,$products)) {
                        array_push($products,$item->product_id);
                    }
                }
            }
        }
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach($products as $id) {
            $product = \App\Product::find($id);
            if($product->dentodeal_enabled && $product->sync_stock_dentodeal) {
                $qty = NULL;
                $sku = NULL;
                $bundleItemsModel = \App\ProductBundleItem::where('product_id',$product->id)->first();
                if($bundleItemsModel) {
                    $bundleModel = \App\ProductBundle::find($bundleItemsModel->bundle_id);
                    if($bundleModel && $bundleModel->sku) {
                        $bundleQty = 1000000000000;
                        foreach($bundleModel->items as $item) {
                            $itemStock = $item->product->availableStock(1);
                            $possibelQty = floor($itemStock / $item->qty);
                            if ($possibelQty < $bundleQty) {
                                $bundleQty = $possibelQty;
                            }
                        }
                        $sku = $bundleModel->sku;
                        $qty = $possibelQty;
                    }
                } elseif($product->dentodeal_bundled && $product->dentodeal_bundle_qty) {
                    $sku = $product->sku;
                    $qty = floor($product->availableStock(1) / $product->dentodeal_bundle_qty);
                } else {
                    $sku = $product->sku;
                    $qty = $product->availableStock(1);
                }

                \App\Jobs\updateDentodealStock::dispatch($qty,$sku);
            }
            $bar->advance();
        }
        $bar->finish();
        Log::info('dentodeal:update command finished at '.\Carbon\Carbon::now()->toString());
    }
}
