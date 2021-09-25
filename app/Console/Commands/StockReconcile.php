<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\StockMismatch;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
class StockReconcile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:reconsile {filename=stock_mismatch}';

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
        $arr = [];
        $initialStockArr = collect(\App\StockEntryItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
        ->join('products','products.id','=','stock_entry_items.product_id')
        ->groupBy('stock_entry_items.product_id')
        ->get())->keyBy('id');
        $soldArr = collect(\App\SaleOrderItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
        ->join('sale_orders','sale_orders.id','=','sale_order_items.sale_order_id')
        ->join('products','products.id','=','sale_order_items.product_id')
        ->where('sale_orders.status','<>','Cancelled')
        ->groupBy('sale_order_items.product_id')
        ->where('sale_orders.created_at','>','2021-05-25 00:00:00')
        ->get()
        )->keyBy('id');
        $returnedArr = collect(\App\SaleReturnItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
        ->join('sale_returns','sale_returns.id','=','sale_return_items.sale_return_id')
        ->join('products','products.id','=','sale_return_items.product_id')
        ->where('sale_returns.status','<>','Draft')->groupBy('sale_return_items.product_id')
        ->where('sale_returns.created_at','>','2021-05-25 00:00:00')
        ->get()
        )->keyBy('id');
        $purchasedArr = collect(\App\GoodsReceiveNoteItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
        ->join('goods_receive_notes','goods_receive_notes.id','=','goods_receive_note_items.grn_id')
        ->join('products','products.id','=','goods_receive_note_items.product_id')
        ->where('goods_receive_notes.status','Active')->groupBy('goods_receive_note_items.product_id')
        ->where('goods_receive_notes.created_at','>','2021-05-25 00:00:00')
        ->get()
        )->keyBy('id');
        $availableStockArr = collect(\App\ProductStock::selectRaw('products.id,products.name,SUM(qty) AS qty')
        ->where('reservable_id',0)
        ->join('products','products.id','=','product_stock.product_id')
        ->groupBy('product_stock.product_id')
        ->get())->keyBy('id');
        $costArr = collect(\App\Product::selectRaw('products.id,products.name,a.pur_id,purchase_items.cost')
        ->join(
            DB::raw('(SELECT products.id, MAX(purchase_items.id) AS pur_id FROM purchase_items JOIN products ON products.id = purchase_items.product_id JOIN purchases ON purchases.id = purchase_items.purchase_id WHERE purchases.status IN (\'Admin Approved\',\'Active\') GROUP BY products.id) AS a')
            ,'a.id','=','products.id'
        )->join('purchase_items','purchase_items.id','=','a.pur_id')
        ->get())->keyBy('id');
        foreach($products as $product) {
            $initialStock = $initialStockArr->has($product->id) ? $initialStockArr[$product->id]['qty'] : 0;
            $sold = $soldArr->has($product->id) ? $soldArr[$product->id]['qty'] : 0;
            $purchased = $purchasedArr->has($product->id) ? $purchasedArr[$product->id]['qty'] : 0;
            $returned = $returnedArr->has($product->id) ? $returnedArr[$product->id]['qty'] : 0;
            $availableStock = $availableStockArr->has($product->id) ? $availableStockArr[$product->id]['qty'] : 0;
            $cost = $costArr->has($product->id) ? $costArr[$product->id]['cost'] : 0;
            $expectedStock = (int)$initialStock + (int)$purchased + (int)$returned - (int)$sold;
            /*
            $initialStock = (int)$product->stock_entry_items()->sum('qty');
            $sold = (int)$product->sale_items()->wherehas('sale_order', function(Builder $query) {
                $query->where('status','<>','Cancelled');
            })->where('created_at','>','2021-05-25 00:00:00')->sum('qty');
            $returned = (int)$product->sale_return_items()->whereHas('sale_return', function(Builder $query) {
                $query->where('status','<>','Draft');
            })->where('created_at','>','2021-05-25 00:00:00')->sum('qty');
            $purchased = (int)$product->grn_items()->whereHas('grn', function(Builder $query) {
                $query->where('status','Active');
            })->where('created_at','>','2021-05-25 00:00:00')->sum('qty');
            $availableStock = (int)$product->availableStock(1);
            $expectedStock = (int)$initialStock + $purchased + $returned - $sold;
            $cost = $product->getCost();
            /*
            if($expectedStock !== $availableStock) {
                $arr[] = [$product->id,$product->name,$initialStock, $purchased, $returned, $sold,$expectedStock,$availableStock];
            }
            */
            $arr[] = [$product->id, $product->name, $product->sku, $initialStock, $purchased, $returned, $sold,$expectedStock,$availableStock, $cost, $product->gst];
            $bar->advance();
        }
        $bar->finish();
        $filename = $this->argument('filename');
        Excel::store(new StockMismatch($arr), $filename.'.xlsx','public');
    }
}
